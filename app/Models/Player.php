<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\MatchModel;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'jersey_number',
        'avatar',
        'birth_date',
        'nationality',
        'height',
        'weight',
        'rating',
        'goals_scored',
        'assists',
        'clean_sheets',
        'yellow_cards',
        'red_cards',
        'team_id',
        'bio'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    protected $appends = ['age'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function matchEvents()
    {
        return $this->hasMany(MatchEvent::class);
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    public function scopeTopScorers($query)
    {
        return $query->orderBy('goals_scored', 'desc');
    }

    public function scopeTopRated($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    // ==================== SKILLS & ATTRIBUTES ====================

    /**
     * Get shooting skill based on goals scored and position
     */
    public function getShootingSkillAttribute()
    {
        $baseSkill = 50; // Base skill

        if ($this->position === 'GK') {
            return $baseSkill; // Goalkeepers don't shoot much
        }

        // Calculate based on goals per match (assuming 30 matches per season)
        $matches = 30;
        $goalsPerMatch = $this->goals_scored / $matches;

        if ($this->position === 'FWD') {
            $multiplier = 15; // Forwards get higher shooting skill
        } elseif ($this->position === 'MID') {
            $multiplier = 12; // Midfielders get medium shooting skill
        } else {
            $multiplier = 8;  // Defenders get lower shooting skill
        }

        $skill = $baseSkill + ($goalsPerMatch * $multiplier);
        return min(100, max(20, round($skill))); // Clamp between 20-100
    }

    /**
     * Get dribbling skill based on position and rating
     */
    public function getDribblingSkillAttribute()
    {
        $baseSkill = 40;

        if ($this->position === 'GK') {
            return $baseSkill; // Goalkeepers don't dribble
        }

        // Base on position and rating
        $positionMultiplier = match($this->position) {
            'FWD' => 1.5,    // Forwards are best dribblers
            'MID' => 1.3,    // Midfielders are good dribblers
            'DEF' => 0.8,    // Defenders are okay dribblers
            default => 1.0
        };

        $skill = $baseSkill + (($this->rating - 3.0) * 20 * $positionMultiplier);
        return min(100, max(20, round($skill)));
    }

    /**
     * Get passing skill based on assists and position
     */
    public function getPassingSkillAttribute()
    {
        $baseSkill = 50;

        if ($this->position === 'GK') {
            return $baseSkill; // Goalkeepers have basic passing
        }

        // Calculate based on assists per match
        $matches = 30;
        $assistsPerMatch = $this->assists / $matches;

        $positionMultiplier = match($this->position) {
            'MID' => 1.4,    // Midfielders are best passers
            'DEF' => 1.2,    // Defenders are good passers
            'FWD' => 1.0,    // Forwards are okay passers
            default => 1.0
        };

        $skill = $baseSkill + ($assistsPerMatch * 25 * $positionMultiplier);
        return min(100, max(20, round($skill)));
    }

    /**
     * Get physical skill based on height, weight, and position
     */
    public function getPhysicalSkillAttribute()
    {
        $baseSkill = 50;

        // Height factor (optimal height for football is around 175-185cm)
        $heightFactor = 0;
        if ($this->height) {
            $optimalHeight = 180;
            $heightDiff = abs($this->height - $optimalHeight);
            $heightFactor = max(0, 20 - ($heightDiff * 0.5));
        }

        // Weight factor (optimal BMI range)
        $weightFactor = 0;
        if ($this->height && $this->weight) {
            $heightM = $this->height / 100;
            $bmi = $this->weight / ($heightM * $heightM);

            if ($bmi >= 18.5 && $bmi <= 25) {
                $weightFactor = 20; // Optimal BMI
            } elseif ($bmi >= 17 && $bmi <= 27) {
                $weightFactor = 15; // Good BMI
            } else {
                $weightFactor = 5;  // Poor BMI
            }
        }

        // Position factor
        $positionMultiplier = match($this->position) {
            'DEF' => 1.3,    // Defenders need physical strength
            'MID' => 1.1,    // Midfielders need some strength
            'FWD' => 1.0,    // Forwards need basic strength
            'GK' => 1.2,     // Goalkeepers need strength
            default => 1.0
        };

        $skill = $baseSkill + $heightFactor + $weightFactor;
        $skill = $skill * $positionMultiplier;

        return min(100, max(20, round($skill)));
    }

    /**
     * Get speed skill based on position and rating
     */
    public function getSpeedSkillAttribute()
    {
        $baseSkill = 45;

        if ($this->position === 'GK') {
            return $baseSkill; // Goalkeepers don't need much speed
        }

        // Base on position and rating
        $positionMultiplier = match($this->position) {
            'FWD' => 1.4,    // Forwards need most speed
            'MID' => 1.2,    // Midfielders need good speed
            'DEF' => 1.0,    // Defenders need basic speed
            default => 1.0
        };

        $skill = $baseSkill + (($this->rating - 3.0) * 22 * $positionMultiplier);
        return min(100, max(20, round($skill)));
    }

    /**
     * Get defending skill based on position and clean sheets
     */
    public function getDefendingSkillAttribute()
    {
        $baseSkill = 30;

        if ($this->position === 'FWD') {
            return $baseSkill; // Forwards have basic defending
        }

        // Calculate based on clean sheets and position
        $matches = 30;
        $cleanSheetsPerMatch = $this->clean_sheets / $matches;

        $positionMultiplier = match($this->position) {
            'DEF' => 1.5,    // Defenders are best defenders
            'MID' => 1.2,    // Midfielders are good defenders
            'GK' => 1.3,     // Goalkeepers are good defenders
            default => 1.0
        };

        $skill = $baseSkill + ($cleanSheetsPerMatch * 30 * $positionMultiplier);
        return min(100, max(20, round($skill)));
    }

    /**
     * Get goalkeeping skill (only for goalkeepers)
     */
    public function getGoalkeepingSkillAttribute()
    {
        if ($this->position !== 'GK') {
            return 0; // Only goalkeepers have this skill
        }

        $baseSkill = 50;

        // Calculate based on clean sheets and rating
        $matches = 30;
        $cleanSheetsPerMatch = $this->clean_sheets / $matches;

        $skill = $baseSkill + ($cleanSheetsPerMatch * 40) + (($this->rating - 3.0) * 25);
        return min(100, max(20, round($skill)));
    }

    /**
     * Get overall skill rating
     */
    public function getOverallSkillAttribute()
    {
        $skills = [
            $this->shooting_skill,
            $this->dribbling_skill,
            $this->passing_skill,
            $this->physical_skill,
            $this->speed_skill
        ];

        // Add position-specific skills
        if ($this->position === 'GK') {
            $skills[] = $this->goalkeeping_skill;
        } else {
            $skills[] = $this->defending_skill;
        }

        return round(array_sum($skills) / count($skills));
    }

    /**
     * Get player traits based on skills and performance
     */
    public function getPlayerTraitsAttribute()
    {
        $traits = [];

        // Clinical Finisher trait
        if ($this->shooting_skill >= 80) {
            $traits[] = 'Clinical Finisher';
        }

        // Speed Dribbler trait
        if ($this->speed_skill >= 75 && $this->dribbling_skill >= 70) {
            $traits[] = 'Speed Dribbler';
        }

        // First Touch trait
        if ($this->dribbling_skill >= 75) {
            $traits[] = 'First Touch';
        }

        // Aerial Threat trait
        if ($this->physical_skill >= 75 && $this->height >= 180) {
            $traits[] = 'Aerial Threat';
        }

        // Long Shots trait
        if ($this->shooting_skill >= 70 && $this->position !== 'GK') {
            $traits[] = 'Long Shots';
        }

        // Playmaker trait
        if ($this->passing_skill >= 80 && $this->assists >= 5) {
            $traits[] = 'Playmaker';
        }

        // Tackler trait
        if ($this->defending_skill >= 75 && $this->position !== 'FWD') {
            $traits[] = 'Tackler';
        }

        // Shot Stopper trait
        if ($this->goalkeeping_skill >= 80) {
            $traits[] = 'Shot Stopper';
        }

        return $traits;
    }

    // ==================== CAREER STATISTICS ====================

    /**
     * Get career statistics by season
     */
    public function getCareerStatsAttribute()
    {
        // Use service if available, fallback to old method
        if (app()->bound('App\Services\MatchEventService')) {
            $service = app('App\Services\MatchEventService');

            if ($this->team) {
                $tournaments = $this->team->tournaments()
                    ->wherePivot('status', 'registered')
                    ->orderBy('start_date', 'desc')
                    ->get();

                return $service->getPlayerCareerStats($this->id, $tournaments);
            }
        }

        // Fallback to old method if service not available
        return $this->getLegacyCareerStats();
    }

    /**
     * Legacy method for career stats (fallback)
     */
    private function getLegacyCareerStats(): array
    {
        $careerStats = [];

        // Get tournaments where player's team participated
        if ($this->team) {
            $tournaments = $this->team->tournaments()
                ->wherePivot('status', 'registered')
                ->orderBy('start_date', 'desc')
                ->get();

            foreach ($tournaments as $tournament) {
                $season = $this->getSeasonFromDate($tournament->start_date);

                // Get matches for this player in this tournament
                $matches = MatchModel::where('tournament_id', $tournament->id)
                    ->where(function($query) {
                        $query->where('home_team_id', $this->team_id)
                              ->orWhere('away_team_id', $this->team_id);
                    })
                    ->get();

                // Get actual performance data from match_events
                $matchIds = $matches->pluck('id');
                $goals = 0;
                $assists = 0;
                $yellowCards = 0;
                $redCards = 0;
                $cleanSheets = 0;

                if ($matchIds->isNotEmpty()) {
                    // Get goals and assists
                    $goalEvents = DB::table('match_events')
                        ->whereIn('match_id', $matchIds)
                        ->where('player_id', $this->id)
                        ->where('type', 'goal')
                        ->count();
                    $goals = $goalEvents;

                    $assistEvents = DB::table('match_events')
                        ->whereIn('match_id', $matchIds)
                        ->where('player_id', $this->id)
                        ->where('type', 'assist')
                        ->count();
                    $assists = $assistEvents;

                    // Get cards
                    $yellowEvents = DB::table('match_events')
                        ->whereIn('match_id', $matchIds)
                        ->where('player_id', $this->id)
                        ->where('type', 'yellow_card')
                        ->count();
                    $yellowCards = $yellowEvents;

                    $redEvents = DB::table('match_events')
                        ->whereIn('match_id', $matchIds)
                        ->where('player_id', $this->id)
                        ->where('type', 'red_card')
                        ->count();
                    $redCards = $redEvents;

                    // Calculate clean sheets for goalkeepers and defenders
                    if (in_array($this->position, ['GK', 'DEF'])) {
                        foreach ($matches as $match) {
                            $opponentTeamId = ($match->home_team_id == $this->team_id) ? $match->away_team_id : $match->home_team_id;
                            $opponentGoals = DB::table('match_events')
                                ->where('match_id', $match->id)
                                ->where('team_id', $opponentTeamId)
                                ->where('type', 'goal')
                                ->count();
                            if ($opponentGoals == 0) {
                                $cleanSheets++;
                            }
                        }
                    }
                }

                // Calculate stats for this season
                $seasonStats = [
                    'season' => $season,
                    'team' => $this->team->name,
                    'tournament' => $tournament->name,
                    'matches' => $matches->count(),
                    'goals' => $goals,
                    'assists' => $assists,
                    'clean_sheets' => $cleanSheets,
                    'yellow_cards' => $yellowCards,
                    'red_cards' => $redCards,
                    'minutes' => $matches->count() * 90, // Assuming 90 minutes per match
                ];

                $careerStats[] = $seasonStats;
            }
        }

        // If no tournament data, create dummy career progression
        if (empty($careerStats)) {
            $careerStats = $this->getDummyCareerStats();
        }

        return $careerStats;
    }

    /**
     * Get season from date (e.g., 2023-09-01 → 2023-24)
     */
    private function getSeasonFromDate($date)
    {
        $year = $date->year;
        $month = $date->month;

        // Season runs from August to July
        if ($month >= 8) {
            return $year . '-' . substr($year + 1, -2);
        } else {
            return ($year - 1) . '-' . substr($year, -2);
        }
    }

    /**
     * Get goals for specific season (placeholder - would need season tracking)
     */
    private function getGoalsForSeason($season)
    {
        // This would ideally come from a player_season_stats table
        // For now, distribute current goals across seasons
        $currentGoals = $this->goals_scored ?? 0;
        $seasons = ['2023-24', '2022-23', '2021-22', '2020-21', '2019-20'];

        if (in_array($season, $seasons)) {
            $seasonIndex = array_search($season, $seasons);
            // Distribute goals with more recent seasons having more goals
            $multiplier = 1 - ($seasonIndex * 0.2);
            return max(0, round($currentGoals * $multiplier));
        }

        return 0;
    }

    /**
     * Get assists for specific season (placeholder - would need season tracking)
     */
    private function getAssistsForSeason($season)
    {
        $currentAssists = $this->assists ?? 0;
        $seasons = ['2023-24', '2022-23', '2021-22', '2020-21', '2019-20'];

        if (in_array($season, $seasons)) {
            $seasonIndex = array_search($season, $seasons);
            $multiplier = 1 - ($seasonIndex * 0.2);
            return max(0, round($currentAssists * $multiplier));
        }

        return 0;
    }

    /**
     * Get clean sheets for specific season
     */
    private function getCleanSheetsForSeason($season)
    {
        $currentCleanSheets = $this->clean_sheets ?? 0;
        $seasons = ['2023-24', '2022-23', '2021-22', '2020-21', '2019-20'];

        if (in_array($season, $seasons)) {
            $seasonIndex = array_search($season, $seasons);
            $multiplier = 1 - ($seasonIndex * 0.2);
            return max(0, round($currentCleanSheets * $multiplier));
        }

        return 0;
    }

    /**
     * Get yellow cards for specific season
     */
    private function getYellowCardsForSeason($season)
    {
        $currentYellowCards = $this->yellow_cards ?? 0;
        $seasons = ['2023-24', '2022-23', '2021-22', '2020-21', '2019-20'];

        if (in_array($season, $seasons)) {
            $seasonIndex = array_search($season, $seasons);
            $multiplier = 1 - ($seasonIndex * 0.2);
            return max(0, round($currentYellowCards * $multiplier));
        }

        return 0;
    }

    /**
     * Get red cards for specific season
     */
    private function getRedCardsForSeason($season)
    {
        $currentRedCards = $this->red_cards ?? 0;
        $seasons = ['2023-24', '2022-23', '2021-22', '2020-21', '2019-20'];

        if (in_array($season, $seasons)) {
            $seasonIndex = array_search($season, $seasons);
            $multiplier = 1 - ($seasonIndex * 0.2);
            return max(0, round($currentRedCards * $multiplier));
        }

        return 0;
    }

    /**
     * Get dummy career stats for demonstration
     */
    private function getDummyCareerStats()
    {
        $currentYear = date('Y');
        $currentGoals = $this->goals_scored ?? 0;
        $currentAssists = $this->assists ?? 0;
        $currentCleanSheets = $this->clean_sheets ?? 0;
        $currentYellowCards = $this->yellow_cards ?? 0;
        $currentRedCards = $this->red_cards ?? 0;

        $careerStats = [];

        // Current season
        $careerStats[] = [
            'season' => ($currentYear - 1) . '-' . substr($currentYear, -2),
            'team' => $this->team ? $this->team->name : 'Current Team',
            'tournament' => 'Main League',
            'matches' => 28,
            'goals' => $currentGoals,
            'assists' => $currentAssists,
            'clean_sheets' => $currentCleanSheets,
            'yellow_cards' => $currentYellowCards,
            'red_cards' => $currentRedCards,
            'minutes' => 28 * 90,
        ];

        // Previous seasons with decreasing performance
        for ($i = 1; $i <= 4; $i++) {
            $year = $currentYear - $i;
            $season = ($year - 1) . '-' . substr($year, -2);

            $careerStats[] = [
                'season' => $season,
                'team' => $this->team ? $this->team->name : 'Previous Team',
                'tournament' => 'Main League',
                'matches' => max(20, 35 - ($i * 3)),
                'goals' => max(0, round($currentGoals * (1 - ($i * 0.25)))),
                'assists' => max(0, round($currentAssists * (1 - ($i * 0.25)))),
                'clean_sheets' => max(0, round($currentCleanSheets * (1 - ($i * 0.25)))),
                'yellow_cards' => max(0, round($currentYellowCards * (1 - ($i * 0.25)))),
                'red_cards' => max(0, round($currentRedCards * (1 - ($i * 0.25)))),
                'minutes' => max(20, 35 - ($i * 3)) * 90,
            ];
        }

        return $careerStats;
    }

    /**
     * Get total career statistics
     */
    public function getTotalCareerStatsAttribute()
    {
        $careerStats = $this->career_stats;

        $totals = [
            'matches' => 0,
            'goals' => 0,
            'assists' => 0,
            'clean_sheets' => 0,
            'yellow_cards' => 0,
            'red_cards' => 0,
            'minutes' => 0,
        ];

        foreach ($careerStats as $stat) {
            $totals['matches'] += $stat['matches'];
            $totals['goals'] += $stat['goals'];
            $totals['assists'] += $stat['assists'];
            $totals['clean_sheets'] += $stat['clean_sheets'];
            $totals['yellow_cards'] += $stat['yellow_cards'];
            $totals['red_cards'] += $stat['red_cards'];
            $totals['minutes'] += $stat['minutes'];
        }

        return $totals;
    }

    // ==================== PERFORMANCE CHART DATA ====================

    /**
     * Get performance chart data for goals over seasons
     */
    public function getGoalsChartDataAttribute()
    {
        $careerStats = $this->career_stats;
        $chartData = [
            'labels' => [],
            'data' => [],
            'backgroundColor' => 'rgba(220, 53, 69, 0.2)',
            'borderColor' => 'rgba(220, 53, 69, 1)',
            'tension' => 0.4
        ];

        foreach ($careerStats as $stat) {
            $chartData['labels'][] = $stat['season'];
            $chartData['data'][] = $stat['goals'];
        }

        return $chartData;
    }

    /**
     * Get performance chart data for assists over seasons
     */
    public function getAssistsChartDataAttribute()
    {
        $careerStats = $this->career_stats;
        $chartData = [
            'labels' => [],
            'data' => [],
            'backgroundColor' => 'rgba(13, 202, 240, 0.2)',
            'borderColor' => 'rgba(13, 202, 240, 1)',
            'tension' => 0.4
        ];

        foreach ($careerStats as $stat) {
            $chartData['labels'][] = $stat['season'];
            $chartData['data'][] = $stat['assists'];
        }

        return $chartData;
    }

    /**
     * Get performance chart data for matches over seasons
     */
    public function getMatchesChartDataAttribute()
    {
        $careerStats = $this->career_stats;
        $chartData = [
            'labels' => [],
            'data' => [],
            'backgroundColor' => 'rgba(25, 135, 84, 0.2)',
            'borderColor' => 'rgba(25, 135, 84, 1)',
            'tension' => 0.4
        ];

        foreach ($careerStats as $stat) {
            $chartData['labels'][] = $stat['season'];
            $chartData['data'][] = $stat['matches'];
        }

        return $chartData;
    }

    /**
     * Get performance chart data for skills over time
     */
    public function getSkillsChartDataAttribute()
    {
        $chartData = [
            'labels' => ['Shooting', 'Dribbling', 'Passing', 'Physical', 'Speed'],
            'datasets' => []
        ];

        // Current skills
        $currentSkills = [
            $this->shooting_skill,
            $this->dribbling_skill,
            $this->passing_skill,
            $this->physical_skill,
            $this->speed_skill
        ];

        // Add position-specific skills
        if ($this->position === 'GK') {
            $chartData['labels'][] = 'Goalkeeping';
            $currentSkills[] = $this->goalkeeping_skill;
        } else {
            $chartData['labels'][] = 'Defending';
            $currentSkills[] = $this->defending_skill;
        }

        $chartData['datasets'][] = [
            'label' => 'Current Skills',
            'data' => $currentSkills,
            'backgroundColor' => 'rgba(37, 99, 235, 0.2)',
            'borderColor' => 'rgba(37, 99, 235, 1)',
            'borderWidth' => 2,
            'pointBackgroundColor' => 'rgba(37, 99, 235, 1)',
            'pointBorderColor' => '#fff',
            'pointHoverBackgroundColor' => '#fff',
            'pointHoverBorderColor' => 'rgba(37, 99, 235, 1)'
        ];

        return $chartData;
    }

    /**
     * Get performance chart data for monthly performance (last 12 months)
     */
    public function getMonthlyPerformanceDataAttribute()
    {
        $months = [];
        $goals = [];
        $assists = [];
        $matches = [];

        // Generate last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            // Simulate monthly performance based on current stats
            $monthlyGoals = $this->goals_scored ? round($this->goals_scored / 12) : 0;
            $monthlyAssists = $this->assists ? round($this->assists / 12) : 0;
            $monthlyMatches = $this->matches_count ? round($this->matches_count / 12) : 0;

            // Add some variation to make it realistic
            $variation = rand(-20, 20) / 100; // ±20% variation

            $goals[] = max(0, round($monthlyGoals * (1 + $variation)));
            $assists[] = max(0, round($monthlyAssists * (1 + $variation)));
            $matches[] = max(0, round($monthlyMatches * (1 + $variation)));
        }

        return [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Goals',
                    'data' => $goals,
                    'backgroundColor' => 'rgba(220, 53, 69, 0.2)',
                    'borderColor' => 'rgba(220, 53, 69, 1)',
                    'tension' => 0.4,
                    'fill' => false
                ],
                [
                    'label' => 'Assists',
                    'data' => $assists,
                    'backgroundColor' => 'rgba(13, 202, 240, 0.2)',
                    'borderColor' => 'rgba(13, 202, 240, 1)',
                    'tension' => 0.4,
                    'fill' => false
                ],
                [
                    'label' => 'Matches',
                    'data' => $matches,
                    'backgroundColor' => 'rgba(25, 135, 84, 0.2)',
                    'borderColor' => 'rgba(25, 135, 84, 1)',
                    'tension' => 0.4,
                    'fill' => false
                ]
            ]
        ];
    }

    /**
     * Get performance comparison with team average
     */
    public function getTeamComparisonDataAttribute()
    {
        if (!$this->team) {
            return null;
        }

        // Get team average stats (simulated for now)
        $teamAvgGoals = $this->team->players()->avg('goals_scored') ?? 0;
        $teamAvgAssists = $this->team->players()->avg('assists') ?? 0;
        $teamAvgRating = $this->team->players()->avg('rating') ?? 0;

        $playerGoals = $this->goals_scored ?? 0;
        $playerAssists = $this->assists ?? 0;
        $playerRating = $this->rating ?? 0;

        return [
            'labels' => ['Goals', 'Assists', 'Rating'],
            'datasets' => [
                [
                    'label' => 'Player Performance',
                    'data' => [$playerGoals, $playerAssists, $playerRating * 20], // Convert rating to 0-100 scale
                    'backgroundColor' => 'rgba(37, 99, 235, 0.8)',
                    'borderColor' => 'rgba(37, 99, 235, 1)',
                    'borderWidth' => 2
                ],
                [
                    'label' => 'Team Average',
                    'data' => [$teamAvgGoals, $teamAvgAssists, $teamAvgRating * 20],
                    'backgroundColor' => 'rgba(156, 163, 175, 0.8)',
                    'borderColor' => 'rgba(156, 163, 175, 1)',
                    'borderWidth' => 2
                ]
            ]
        ];
    }
}

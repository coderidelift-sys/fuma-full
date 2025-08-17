<?php

namespace Database\Seeders;

use App\Models\MatchEvent;
use App\Models\MatchModel;
use App\Models\Player;
use App\Models\Role;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ComprehensiveSeeder extends Seeder
{
    /**
     * Seed the application's database with comprehensive, realistic data.
     */
    public function run(): void
    {
        $this->createRoles();
        $this->createVenues();
        $this->createUsers();
        $this->createTeams();
        $this->createPlayers();
        $this->createTournaments();
        $this->createMatches();
        $this->createMatchEvents();
        $this->updateTournamentStandings();
        $this->updatePlayerStats();
    }

    private function createRoles(): void
    {
        $roles = [
            'admin' => 'Administrator',
            'organizer' => 'Tournament Organizer',
            'manager' => 'Team Manager',
            'committee' => 'Committee Member',
            'referee' => 'Match Referee',
        ];

        foreach ($roles as $name => $display) {
            Role::firstOrCreate(['name' => $name, 'display_name' => $display]);
        }
    }

    private function createVenues(): void
    {
        $venues = [
            [
                'name' => 'Gelora Bung Karno Stadium',
                'city' => 'Jakarta',
                'capacity' => 77000,
                'address' => 'Jl. Pintu I Senayan, Jakarta Pusat',
                'description' => 'National stadium and main venue for major football events'
            ],
            [
                'name' => 'Jakabaring Sport City',
                'city' => 'Palembang',
                'capacity' => 36000,
                'address' => 'Jl. Gubernur H. A. Bastari, Palembang',
                'description' => 'Multi-purpose stadium with modern facilities'
            ],
            [
                'name' => 'Gelora Bandung Lautan Api',
                'city' => 'Bandung',
                'capacity' => 38000,
                'address' => 'Jl. Siliwangi, Bandung',
                'description' => 'Home of Persib Bandung with passionate supporters'
            ],
            [
                'name' => 'Gelora 10 November',
                'city' => 'Surabaya',
                'capacity' => 45000,
                'address' => 'Jl. Pasar Turi, Surabaya',
                'description' => 'Historic stadium with rich football tradition'
            ],
            [
                'name' => 'Gelora Sriwijaya',
                'city' => 'Palembang',
                'capacity' => 23000,
                'address' => 'Jl. Gubernur H. A. Bastari, Palembang',
                'description' => 'Compact stadium perfect for regional tournaments'
            ],
            [
                'name' => 'Gelora Bumi Kartini',
                'city' => 'Jepara',
                'capacity' => 15000,
                'address' => 'Jl. Kartini, Jepara',
                'description' => 'Community stadium for local competitions'
            ]
        ];

        foreach ($venues as $venueData) {
            Venue::firstOrCreate(['name' => $venueData['name']], $venueData);
        }
    }

    private function createUsers(): void
    {
        $users = [
            // Admin users
            [
                'name' => 'Ahmad Rizki',
            'email' => 'admin@fuma.com',
                'password' => 'password',
            'phone' => '081234567890',
                'roles' => ['admin']
            ],
            [
                'name' => 'Sarah Wijaya',
                'email' => 'sarah.admin@fuma.com',
                'password' => 'password',
                'phone' => '081234567891',
                'roles' => ['admin']
            ],
            // Organizers
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.organizer@fuma.com',
                'password' => 'password',
                'phone' => '081234567892',
                'roles' => ['organizer']
            ],
            [
                'name' => 'Dewi Kartika',
                'email' => 'dewi.organizer@fuma.com',
                'password' => 'password',
                'phone' => '081234567893',
                'roles' => ['organizer']
            ],
            [
                'name' => 'Rudi Hermawan',
                'email' => 'rudi.organizer@fuma.com',
                'password' => 'password',
                'phone' => '081234567894',
                'roles' => ['organizer']
            ],
            // Referees
            [
                'name' => 'Joko Supriyadi',
                'email' => 'joko.referee@fuma.com',
                'password' => 'password',
                'phone' => '081234567895',
                'roles' => ['referee']
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.referee@fuma.com',
                'password' => 'password',
                'phone' => '081234567896',
                'roles' => ['referee']
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.referee@fuma.com',
                'password' => 'password',
                'phone' => '081234567897',
                'roles' => ['referee']
            ]
        ];

        foreach ($users as $userData) {
            $roles = $userData['roles'];
            unset($userData['roles']);

            $user = User::firstOrCreate(['email' => $userData['email']], [
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'phone' => $userData['phone']
            ]);

            foreach ($roles as $roleName) {
                $role = Role::where('name', $roleName)->first();
                if ($role && !DB::table('user_roles')->where('user_id', $user->id)->where('role_id', $role->id)->exists()) {
                    DB::table('user_roles')->insert([
                        'role_id' => $role->id,
                        'user_id' => $user->id
                    ]);
                }
            }
        }
    }

    private function createTeams(): void
    {
        $teams = [
            // Jakarta Teams
            [
                'name' => 'Persija Jakarta',
                'short_name' => 'PSJ',
                'city' => 'Jakarta',
                'country' => 'Indonesia',
                'founded_year' => 1928,
                'stadium' => 'Gelora Bung Karno Stadium',
                'capacity' => 77000,
                'primary_color' => '#FF6B35',
                'secondary_color' => '#004E89',
                'manager_name' => 'Thomas Doll',
                'manager_phone' => '081234567898',
                'manager_email' => 'thomas.doll@persija.co.id',
                'description' => 'The pride of Jakarta, founded in 1928. Known for passionate supporters and attacking football.',
                'rating' => 5,
                'trophies_count' => 12
            ],
            [
                'name' => 'Bhayangkara FC',
                'short_name' => 'BFC',
                'city' => 'Jakarta',
                'country' => 'Indonesia',
                'founded_year' => 2015,
                'stadium' => 'Gelora Bung Karno Stadium',
                'capacity' => 77000,
                'primary_color' => '#1E3A8A',
                'secondary_color' => '#F59E0B',
                'manager_name' => 'Alfred Riedl',
                'manager_phone' => '081234567899',
                'manager_email' => 'alfred.riedl@bhayangkara.co.id',
                'description' => 'Police-affiliated club with strong defensive organization.',
                'rating' => 4,
                'trophies_count' => 2
            ],
            // Bandung Teams
            [
                'name' => 'Persib Bandung',
                'short_name' => 'PSB',
                'city' => 'Bandung',
                'country' => 'Indonesia',
                'founded_year' => 1933,
                'stadium' => 'Gelora Bandung Lautan Api',
                'capacity' => 38000,
                'primary_color' => '#DC2626',
                'secondary_color' => '#1F2937',
                'manager_name' => 'Luis Milla',
                'manager_phone' => '081234567900',
                'manager_email' => 'luis.milla@persib.co.id',
                'description' => 'West Java\'s most successful club with passionate supporters known as Bobotoh.',
                'rating' => 5,
                'trophies_count' => 15
            ],
            [
                'name' => 'Bandung United',
                'short_name' => 'BUN',
                'city' => 'Bandung',
                'country' => 'Indonesia',
                'founded_year' => 2010,
                'stadium' => 'Gelora Bandung Lautan Api',
                'capacity' => 38000,
                'primary_color' => '#059669',
                'secondary_color' => '#FFFFFF',
                'manager_name' => 'Robby Darwis',
                'manager_phone' => '081234567901',
                'manager_email' => 'robby.darwis@bandungunited.co.id',
                'description' => 'Modern club with focus on youth development and attacking football.',
                'rating' => 3,
                'trophies_count' => 1
            ],
            // Surabaya Teams
            [
                'name' => 'Persebaya Surabaya',
                'short_name' => 'PSB',
                'city' => 'Surabaya',
                'country' => 'Indonesia',
                'founded_year' => 1927,
                'stadium' => 'Gelora 10 November',
                'capacity' => 45000,
                'primary_color' => '#059669',
                'secondary_color' => '#FFFFFF',
                'manager_name' => 'Aji Santoso',
                'manager_phone' => '081234567902',
                'manager_email' => 'aji.santoso@persebaya.co.id',
                'description' => 'East Java\'s pride with rich history and loyal supporters.',
                'rating' => 4,
                'trophies_count' => 8
            ],
            [
                'name' => 'Arema FC',
                'short_name' => 'ARM',
                'city' => 'Malang',
                'country' => 'Indonesia',
                'founded_year' => 1987,
                'stadium' => 'Gelora Bung Karno Stadium',
                'capacity' => 77000,
                'primary_color' => '#DC2626',
                'secondary_color' => '#1F2937',
                'manager_name' => 'Eduardo Almeida',
                'manager_phone' => '081234567903',
                'manager_email' => 'eduardo.almeida@arema.co.id',
                'description' => 'Malang-based club known for attacking style and passionate fans.',
                'rating' => 4,
                'trophies_count' => 6
            ],
            // Palembang Teams
            [
                'name' => 'Sriwijaya FC',
                'short_name' => 'SRW',
                'city' => 'Palembang',
                'country' => 'Indonesia',
                'founded_year' => 1976,
                'stadium' => 'Gelora Sriwijaya',
                'capacity' => 23000,
                'primary_color' => '#1E40AF',
                'secondary_color' => '#F59E0B',
                'manager_name' => 'Rahmad Darmawan',
                'manager_phone' => '081234567904',
                'manager_email' => 'rahmad.darmawan@sriwijaya.co.id',
                'description' => 'South Sumatra\'s most successful club with strong local support.',
                'rating' => 3,
                'trophies_count' => 4
            ],
            [
                'name' => 'PSMS Medan',
                'short_name' => 'PSM',
                'city' => 'Medan',
                'country' => 'Indonesia',
                'founded_year' => 1950,
                'stadium' => 'Gelora Bumi Kartini',
                'capacity' => 15000,
                'primary_color' => '#DC2626',
                'secondary_color' => '#1F2937',
                'manager_name' => 'Rahmad Darmawan',
                'manager_phone' => '081234567905',
                'manager_email' => 'rahmad.darmawan@psms.co.id',
                'description' => 'North Sumatra\'s historic club with passionate supporters.',
                'rating' => 3,
                'trophies_count' => 3
            ]
        ];

        foreach ($teams as $teamData) {
            Team::firstOrCreate(['name' => $teamData['name']], $teamData);
        }
    }

    private function createPlayers(): void
    {
        $teams = Team::all();

        foreach ($teams as $team) {
            $this->createTeamPlayers($team);
        }
    }

    private function createTeamPlayers(Team $team): void
    {
        $positions = [
            'GK' => ['count' => 3, 'names' => ['Goalkeeper', 'Keeper', 'Shot Stopper']],
            'DEF' => ['count' => 8, 'names' => ['Defender', 'Full Back', 'Center Back', 'Wing Back']],
            'MID' => ['count' => 8, 'names' => ['Midfielder', 'Central Mid', 'Winger', 'Attacking Mid']],
            'FWD' => ['count' => 4, 'names' => ['Forward', 'Striker', 'Winger', 'Second Striker']]
        ];

        $jerseyNumber = 1;

        foreach ($positions as $position => $config) {
            for ($i = 0; $i < $config['count']; $i++) {
                $isCaptain = ($position === 'DEF' && $i === 0) || ($position === 'MID' && $i === 0);

                Player::create([
                    'name' => $this->generateIndonesianName(),
                    'position' => $position,
                    'jersey_number' => $jerseyNumber,
                    'birth_date' => $this->generateRealisticBirthDate($position),
                    'nationality' => 'Indonesia',
                    'height' => $this->generateRealisticHeight($position),
                    'weight' => $this->generateRealisticWeight($position),
                    'rating' => $this->generateRealisticRating($position, $isCaptain),
                    'team_id' => $team->id,
                    'is_captain' => $isCaptain,
                    'goals_scored' => 0,
                    'assists' => 0,
                    'clean_sheets' => 0,
                    'yellow_cards' => 0,
                    'red_cards' => 0
                ]);

                $jerseyNumber++;
            }
        }
    }

    private function generateIndonesianName(): string
    {
        $firstNames = [
            'Ahmad',
            'Muhammad',
            'Abdul',
            'Rizki',
            'Budi',
            'Dewi',
            'Siti',
            'Rudi',
            'Joko',
            'Sari',
            'Agus',
            'Eko',
            'Tri',
            'Dedi',
            'Yudi',
            'Wati',
            'Nur',
            'Hadi',
            'Tono',
            'Susi',
            'Bambang',
            'Slamet',
            'Supri',
            'Wahyu',
            'Hendra',
            'Indra',
            'Doni',
            'Roni',
            'Toni',
            'Yuni'
        ];

        $lastNames = [
            'Santoso',
            'Wijaya',
            'Kartika',
            'Hermawan',
            'Supriyadi',
            'Nurhaliza',
            'Fauzi',
            'Prasetyo',
            'Kusuma',
            'Putra',
            'Saputra',
            'Hidayat',
            'Rachman',
            'Siregar',
            'Nasution',
            'Lubis',
            'Siregar',
            'Harahap',
            'Hasibuan',
            'Simanjuntak',
            'Sitompul',
            'Sihombing',
            'Pangaribuan'
        ];

        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    private function generateRealisticBirthDate(string $position): Carbon
    {
        $currentYear = now()->year;

        // Different age ranges for different positions
        $ageRanges = [
            'GK' => [18, 35],    // Goalkeepers can play longer
            'DEF' => [18, 32],   // Defenders peak later
            'MID' => [17, 30],   // Midfielders peak earlier
            'FWD' => [17, 28]    // Forwards peak earliest
        ];

        $range = $ageRanges[$position];
        $age = rand($range[0], $range[1]);
        $birthYear = $currentYear - $age;

        return Carbon::create($birthYear, rand(1, 12), rand(1, 28));
    }

    private function generateRealisticHeight(string $position): int
    {
        $heightRanges = [
            'GK' => [175, 195],  // Goalkeepers are usually taller
            'DEF' => [175, 190], // Defenders are often tall
            'MID' => [165, 185], // Midfielders vary more
            'FWD' => [170, 185]  // Forwards are often medium to tall
        ];

        $range = $heightRanges[$position];
        return rand($range[0], $range[1]);
    }

    private function generateRealisticWeight(string $position): int
    {
        $weightRanges = [
            'GK' => [70, 85],   // Goalkeepers are often heavier
            'DEF' => [70, 80],  // Defenders are usually strong
            'MID' => [65, 75],  // Midfielders are balanced
            'FWD' => [65, 75]   // Forwards are often lean
        ];

        $range = $weightRanges[$position];
        return rand($range[0], $range[1]);
    }

    private function generateRealisticRating(string $position, bool $isCaptain): int
    {
        $baseRating = $isCaptain ? 4 : 3;

        // Position-based adjustments
        $positionBonus = [
            'GK' => 0,
            'DEF' => 0,
            'MID' => 1,
            'FWD' => 1
        ];

        $rating = $baseRating + $positionBonus[$position];

        // Add some randomness but keep it realistic
        $rating += rand(-1, 1);

        return max(1, min(5, $rating));
    }

    private function createTournaments(): void
    {
        $tournaments = [
            [
                'name' => 'Liga 1 Indonesia 2023/24',
                'description' => 'Premier professional football league in Indonesia, featuring the country\'s top clubs competing for the national championship.',
                'prize_pool' => 50000000000, // 50 billion IDR
                'start_date' => '2023-07-15',
                'end_date' => '2024-05-30',
                'max_teams' => 18,
                'venue' => 'Multiple Venues',
                'organizer_id' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'organizer');
                })->first()->id,
                'status' => 'ongoing'
            ],
            [
                'name' => 'Piala Indonesia 2023',
                'description' => 'Annual knockout cup competition open to all Indonesian football clubs, from amateur to professional levels.',
                'prize_pool' => 10000000000, // 10 billion IDR
                'start_date' => '2023-02-01',
                'end_date' => '2023-12-15',
                'max_teams' => 32,
                'venue' => 'Multiple Venues',
                'organizer_id' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'organizer');
                })->first()->id,
                'status' => 'completed'
            ],
            [
                'name' => 'Liga 2 Indonesia 2023/24',
                'description' => 'Second tier of Indonesian professional football, serving as a pathway for clubs to reach the top division.',
                'prize_pool' => 15000000000, // 15 billion IDR
                'start_date' => '2023-08-01',
                'end_date' => '2024-06-15',
                'max_teams' => 24,
                'venue' => 'Multiple Venues',
                'organizer_id' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'organizer');
                })->first()->id,
                'status' => 'ongoing'
            ],
            [
                'name' => 'Piala Presiden 2024',
                'description' => 'Pre-season tournament featuring top Indonesian clubs and international teams, organized by the Indonesian Football Association.',
                'prize_pool' => 25000000000, // 25 billion IDR
                'start_date' => '2024-01-15',
                'end_date' => '2024-02-28',
                'max_teams' => 16,
                'venue' => 'Multiple Venues',
                'organizer_id' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'organizer');
                })->first()->id,
                'status' => 'upcoming'
            ],
            [
                'name' => 'Liga 3 Indonesia 2023/24',
                'description' => 'Third tier of Indonesian football, featuring semi-professional and amateur clubs from various regions.',
                'prize_pool' => 5000000000, // 5 billion IDR
                'start_date' => '2023-09-01',
                'end_date' => '2024-07-30',
                'max_teams' => 32,
                'venue' => 'Multiple Venues',
                'organizer_id' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'organizer');
                })->first()->id,
                'status' => 'ongoing'
            ]
        ];

        foreach ($tournaments as $tournamentData) {
            $tournament = Tournament::firstOrCreate(['name' => $tournamentData['name']], $tournamentData);

            // Attach teams to tournaments based on realistic scenarios
            $this->attachTeamsToTournament($tournament);
        }
    }

    private function attachTeamsToTournament(Tournament $tournament): void
    {
        $teams = Team::all();

        // Different team selection logic based on tournament type
        if (str_contains($tournament->name, 'Liga 1')) {
            // Top teams for Liga 1
            $selectedTeams = $teams->take(18);
        } elseif (str_contains($tournament->name, 'Liga 2')) {
            // Mix of teams for Liga 2
            $selectedTeams = $teams->slice(2, 24);
        } elseif (str_contains($tournament->name, 'Liga 3')) {
            // All teams for Liga 3
            $selectedTeams = $teams;
        } else {
            // Cup competitions - mix of teams
            $selectedTeams = $teams->shuffle()->take($tournament->max_teams);
        }

            foreach ($selectedTeams as $team) {
            if (!$tournament->teams()->where('team_id', $team->id)->exists()) {
                $tournament->teams()->attach($team->id, [
                    'status' => 'registered',
                    'points' => 0,
                    'goals_for' => 0,
                    'goals_against' => 0,
                    'goal_difference' => 0,
                    'matches_played' => 0,
                    'wins' => 0,
                    'draws' => 0,
                    'losses' => 0
                ]);
            }
        }
    }

    private function createMatches(): void
    {
        $tournaments = Tournament::all();

        foreach ($tournaments as $tournament) {
            $this->createTournamentMatches($tournament);
        }
    }

    private function createTournamentMatches(Tournament $tournament): void
    {
        $teams = $tournament->teams;
        $totalTeams = $teams->count();

        if ($totalTeams < 2) return;

        // Determine tournament format
        if (str_contains($tournament->name, 'Liga')) {
            $this->createLeagueMatches($tournament, $teams);
        } else {
            $this->createCupMatches($tournament, $teams);
        }
    }

    private function createLeagueMatches(Tournament $tournament, $teams): void
    {
        $teamsArray = $teams->toArray();
        $totalTeams = count($teamsArray);

        // Create home and away matches for each team
        for ($round = 1; $round <= 2; $round++) { // Home and away
            for ($i = 0; $i < $totalTeams; $i++) {
                for ($j = $i + 1; $j < $totalTeams; $j++) {
                    $homeTeam = $round === 1 ? $teamsArray[$i] : $teamsArray[$j];
                    $awayTeam = $round === 1 ? $teamsArray[$j] : $teamsArray[$i];

                    $this->createMatch($tournament, $homeTeam, $awayTeam, 'league');
                }
            }
        }
    }

    private function createCupMatches(Tournament $tournament, $teams): void
    {
        $teamsArray = $teams->shuffle()->toArray();
        $totalTeams = count($teamsArray);

        // Create knockout matches
        $stages = ['group', 'round_of_32', 'round_of_16', 'quarter_final', 'semi_final', 'final'];

        foreach ($stages as $stage) {
            $teamsInStage = $totalTeams;
            if ($stage === 'round_of_16') $teamsInStage = 16;
            elseif ($stage === 'quarter_final') $teamsInStage = 8;
            elseif ($stage === 'semi_final') $teamsInStage = 4;
            elseif ($stage === 'final') $teamsInStage = 2;

            for ($i = 0; $i < $teamsInStage; $i += 2) {
                if (isset($teamsArray[$i]) && isset($teamsArray[$i + 1])) {
                    $this->createMatch($tournament, $teamsArray[$i], $teamsArray[$i + 1], $stage);
                }
            }
        }
    }

    private function createMatch(Tournament $tournament, $homeTeam, $awayTeam, string $stage): void
    {
        // Determine match date based on tournament status
        $matchDate = $this->generateMatchDate($tournament);

        // Determine match status based on date
        $status = $this->determineMatchStatus($matchDate, $tournament->status);

        // Generate realistic scores for completed matches
        $homeScore = $status === 'completed' ? rand(0, 4) : null;
        $awayScore = $status === 'completed' ? rand(0, 4) : null;

        // Ensure some matches end in draws for realism
        if ($status === 'completed' && rand(1, 4) === 1) {
            $homeScore = $awayScore = rand(0, 2);
        }

        MatchModel::create([
            'tournament_id' => $tournament->id,
            'home_team_id' => $homeTeam['id'],
            'away_team_id' => $awayTeam['id'],
            'stage' => $stage,
            'status' => $status,
            'scheduled_at' => $matchDate,
            'venue' => $tournament->venue,
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'referee' => $this->getRandomReferee()
        ]);
    }

    private function generateMatchDate(Tournament $tournament): Carbon
    {
        $startDate = Carbon::parse($tournament->start_date);
        $endDate = Carbon::parse($tournament->end_date);

        // Generate dates throughout the tournament period
        $daysOffset = rand(0, $startDate->diffInDays($endDate));
        $matchDate = $startDate->copy()->addDays($daysOffset);

        // Ensure match is on a weekend (Saturday or Sunday) for realism
        while ($matchDate->isWeekday()) {
            $matchDate->addDay();
        }

        // Set match time to evening (7 PM or 8 PM)
        $matchDate->setTime(rand(19, 20), 0);

        return $matchDate;
    }

    private function determineMatchStatus(Carbon $matchDate, string $tournamentStatus): string
    {
        $now = now();

        if ($tournamentStatus === 'completed') {
            return 'completed';
        } elseif ($tournamentStatus === 'upcoming') {
            return 'scheduled';
        } else {
            // Ongoing tournament
            if ($matchDate->isPast()) {
                return 'completed';
            } elseif ($matchDate->diffInHours($now) < 3) {
                return 'live';
            } else {
                return 'scheduled';
            }
        }
    }

    private function getRandomReferee(): string
    {
        $referees = [
            'Joko Supriyadi',
            'Siti Nurhaliza',
            'Ahmad Fauzi',
            'Budi Santoso',
            'Dewi Kartika',
            'Rudi Hermawan'
        ];

        return $referees[array_rand($referees)];
    }

    private function createMatchEvents(): void
    {
        $matches = MatchModel::where('status', 'completed')->get();

        foreach ($matches as $match) {
            $this->createMatchEventsForMatch($match);
        }
    }

    private function createMatchEventsForMatch(MatchModel $match): void
    {
        $homeScore = $match->home_score ?? 0;
        $awayScore = $match->away_score ?? 0;

        // Create goal events
        $this->createGoalEvents($match, $homeScore, $awayScore);

        // Create card events
        $this->createCardEvents($match);

        // Create substitution events
        $this->createSubstitutionEvents($match);
    }

    private function createGoalEvents(MatchModel $match, int $homeScore, int $awayScore): void
    {
        $homeTeam = $match->homeTeam;
        $awayTeam = $match->awayTeam;

        // Home team goals
        for ($i = 0; $i < $homeScore; $i++) {
            $scorer = $homeTeam->players()->inRandomOrder()->first();
            if ($scorer) {
                MatchEvent::create([
                    'match_id' => $match->id,
                    'player_id' => $scorer->id,
                    'team_id' => $homeTeam->id,
                    'type' => 'goal',
                    'minute' => $this->generateRealisticMinute(),
                    'description' => 'Goal scored by ' . $scorer->name
                ]);
            }
        }

        // Away team goals
        for ($i = 0; $i < $awayScore; $i++) {
            $scorer = $awayTeam->players()->inRandomOrder()->first();
            if ($scorer) {
                MatchEvent::create([
                    'match_id' => $match->id,
                    'player_id' => $scorer->id,
                    'team_id' => $awayTeam->id,
                    'type' => 'goal',
                    'minute' => $this->generateRealisticMinute(),
                    'description' => 'Goal scored by ' . $scorer->name
                ]);
            }
        }
    }

    private function createCardEvents(MatchModel $match): void
    {
        $totalCards = rand(2, 8); // Realistic number of cards per match

        for ($i = 0; $i < $totalCards; $i++) {
            $team = rand(0, 1) === 0 ? $match->homeTeam : $match->awayTeam;
            $player = $team->players()->inRandomOrder()->first();

            if ($player) {
                $cardType = rand(1, 10) === 1 ? 'red_card' : 'yellow_card';

                MatchEvent::create([
                    'match_id' => $match->id,
                    'player_id' => $player->id,
                    'team_id' => $team->id,
                    'type' => $cardType,
                    'minute' => $this->generateRealisticMinute(),
                    'description' => ucfirst(str_replace('_', ' ', $cardType)) . ' for ' . $player->name
                ]);
            }
        }
    }

    private function createSubstitutionEvents(MatchModel $match): void
    {
        $substitutions = rand(3, 6); // Realistic number of substitutions

        for ($i = 0; $i < $substitutions; $i++) {
            $team = rand(0, 1) === 0 ? $match->homeTeam : $match->awayTeam;
            $player = $team->players()->inRandomOrder()->first();

            if ($player) {
                MatchEvent::create([
                    'match_id' => $match->id,
                    'player_id' => $player->id,
                    'team_id' => $team->id,
                    'type' => 'substitution',
                    'minute' => $this->generateRealisticMinute(),
                    'description' => 'Substitution: ' . $player->name . ' comes on'
                ]);
            }
        }
    }

    private function generateRealisticMinute(): int
    {
        // Generate realistic match minutes
        $minutes = [];

        // First half (1-45)
        for ($i = 1; $i <= 45; $i++) {
            $minutes[] = $i;
        }

        // Second half (46-90)
        for ($i = 46; $i <= 90; $i++) {
            $minutes[] = $i;
        }

        // Extra time (90+)
        for ($i = 1; $i <= 5; $i++) {
            $minutes[] = 90 + $i;
        }

        return $minutes[array_rand($minutes)];
    }

    private function updateTournamentStandings(): void
    {
        $tournaments = Tournament::all();

        foreach ($tournaments as $tournament) {
            $this->updateTournamentStanding($tournament);
        }
    }

    private function updateTournamentStanding(Tournament $tournament): void
    {
        $teams = $tournament->teams;

        foreach ($teams as $team) {
            $matches = MatchModel::where('tournament_id', $tournament->id)
                ->where('status', 'completed')
                ->where(function ($query) use ($team) {
                    $query->where('home_team_id', $team->id)
                        ->orWhere('away_team_id', $team->id);
                })->get();

            $wins = 0;
            $draws = 0;
            $losses = 0;
            $goalsFor = 0;
            $goalsAgainst = 0;

            foreach ($matches as $match) {
                if ($match->home_team_id === $team->id) {
                    $teamScore = $match->home_score ?? 0;
                    $opponentScore = $match->away_score ?? 0;
                } else {
                    $teamScore = $match->away_score ?? 0;
                    $opponentScore = $match->home_score ?? 0;
                }

                $goalsFor += $teamScore;
                $goalsAgainst += $opponentScore;

                if ($teamScore > $opponentScore) {
                    $wins++;
                } elseif ($teamScore === $opponentScore) {
                    $draws++;
                } else {
                    $losses++;
                }
            }

            $points = ($wins * 3) + $draws;
            $goalDifference = $goalsFor - $goalsAgainst;
            $matchesPlayed = $wins + $draws + $losses;

            $tournament->teams()->updateExistingPivot($team->id, [
                'points' => $points,
                'matches_played' => $matchesPlayed,
                'wins' => $wins,
                'draws' => $draws,
                'losses' => $losses,
                'goals_for' => $goalsFor,
                'goals_against' => $goalsAgainst,
                'goal_difference' => $goalDifference
            ]);
        }
    }

    private function updatePlayerStats(): void
    {
        $players = Player::all();

        foreach ($players as $player) {
            $this->updatePlayerStatistics($player);
        }
    }

    private function updatePlayerStatistics(Player $player): void
    {
        $matchEvents = MatchEvent::where('player_id', $player->id)->get();

        $goals = $matchEvents->where('type', 'goal')->count();
        $assists = 0; // Would need more complex logic for assists
        $cleanSheets = 0; // Would need goalkeeper logic
        $yellowCards = $matchEvents->where('type', 'yellow_card')->count();
        $redCards = $matchEvents->where('type', 'red_card')->count();

        $player->update([
            'goals_scored' => $goals,
            'assists' => $assists,
            'clean_sheets' => $cleanSheets,
            'yellow_cards' => $yellowCards,
            'red_cards' => $redCards
        ]);
    }
}

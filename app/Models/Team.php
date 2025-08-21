<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'description',
        'logo',
        'founded_year',
        'city',
        'country',
        'manager_name',
        'manager_phone',
        'manager_email',
        'rating',
        'trophies_count',
        'manager_id',
        'nickname',
        'stadium',
        'capacity',
        'primary_color',
        'secondary_color',
        'website',
        'status'
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'founded_year' => 'integer',
    ];

    protected $appends = [
        'players_count',
        'tournaments_count',
    ];

    /**
     * Always include relation counts to avoid N+1 when serialized with appends.
     */
    protected $withCount = [
        'players',
        'tournaments',
    ];

    protected static function boot()
    {
        parent::boot();

        // Validation sebelum save
        static::saving(function ($team) {
            // Validate rating range
            if ($team->rating < 0 || $team->rating > 100) {
                throw new \InvalidArgumentException('Rating must be between 0-100');
            }

            // Validate trophies count
            if ($team->trophies_count < 0) {
                throw new \InvalidArgumentException('Trophies count cannot be negative');
            }

            // Validate founded year
            if ($team->founded_year && ($team->founded_year < 1800 || $team->founded_year > 2100)) {
                throw new \InvalidArgumentException('Founded year must be between 1800-2100');
            }
        });

        // Cache invalidation setelah update
        static::updated(function ($team) {
            // Clear related caches
            if ($team->wasChanged(['rating', 'trophies_count'])) {
                \App\Services\CacheService::forgetPattern('home_top_teams');
                \App\Services\CacheService::forgetPattern('teams_data');
            }
        });
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'tournament_teams')
            ->withPivot(['status', 'points', 'goals_for', 'goals_against', 'goal_difference', 'matches_played', 'wins', 'draws', 'losses'])
            ->withTimestamps();
    }

    public function homeMatches()
    {
        return $this->hasMany(MatchModel::class, 'home_team_id');
    }

    public function awayMatches()
    {
        return $this->hasMany(MatchModel::class, 'away_team_id');
    }

    /**
     * Get all matches for the team (both home and away)
     */
    public function matches()
    {
        return $this->homeMatches->merge($this->awayMatches);
    }

    public function getAllMatchesAttribute()
    {
        return $this->homeMatches->merge($this->awayMatches);
    }

    public function scopeTopRated($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function getPlayersCountAttribute($value)
    {
        return $value !== null ? (int) $value : $this->players()->count();
    }

    public function getTournamentsCountAttribute($value)
    {
        return $value !== null ? (int) $value : $this->tournaments()->count();
    }
}

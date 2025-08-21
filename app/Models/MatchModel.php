<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchModel extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'tournament_id',
        'home_team_id',
        'away_team_id',
        'stage',
        'status',
        'scheduled_at',
        'venue',
        'venue_id',
        'referee',
        'home_score',
        'away_score',
        'current_minute',
        'duration',
        'weather',
        'attendance',
        'officials',
        'notes',
        'started_at',
        'paused_at',
        'resumed_at',
        'completed_at'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'paused_at' => 'datetime',
        'resumed_at' => 'datetime',
        'completed_at' => 'datetime',
        'officials' => 'array',
        'duration' => 'integer',
        'current_minute' => 'integer',
        'attendance' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        // Validation sebelum save
        static::saving(function ($match) {
            // Validate scores
            if ($match->home_score !== null && $match->home_score < 0) {
                throw new \InvalidArgumentException('Home score cannot be negative');
            }

            if ($match->away_score !== null && $match->away_score < 0) {
                throw new \InvalidArgumentException('Away score cannot be negative');
            }

            // Validate current minute
            if ($match->current_minute !== null && ($match->current_minute < 0 || $match->current_minute > 120)) {
                throw new \InvalidArgumentException('Current minute must be between 0-120');
            }

            // Validate duration
            if ($match->duration && ($match->duration < 45 || $match->duration > 120)) {
                throw new \InvalidArgumentException('Duration must be between 45-120 minutes');
            }

            // Validate scheduled date
            if ($match->scheduled_at && $match->scheduled_at <= $match->created_at) {
                throw new \InvalidArgumentException('Scheduled date must be after creation date');
            }
        });

        // Cache invalidation setelah update
        static::updated(function ($match) {
            // Clear related caches
            if ($match->wasChanged(['status', 'home_score', 'away_score', 'current_minute'])) {
                \App\Services\CacheService::forget("match_stats_{$match->id}");
                \App\Services\CacheService::forgetPattern('home_upcoming_matches');
                \App\Services\CacheService::forgetPattern('tournament_standings');
            }
        });
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function events()
    {
        return $this->hasMany(MatchEvent::class, 'match_id');
    }

    public function matchEvents()
    {
        return $this->hasMany(MatchEvent::class, 'match_id');
    }

    public function lineups()
    {
        return $this->hasMany(MatchLineup::class, 'match_id');
    }

    public function commentary()
    {
        return $this->hasMany(MatchCommentary::class, 'match_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_at', '>', now());
    }

    public function scopeLive($query)
    {
        return $query->where('status', 'live');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByStage($query, $stage)
    {
        return $query->where('stage', $stage);
    }

    public function getWinnerAttribute()
    {
        if ($this->status !== 'completed' || is_null($this->home_score) || is_null($this->away_score)) {
            return null;
        }

        if ($this->home_score > $this->away_score) {
            return $this->homeTeam;
        } elseif ($this->away_score > $this->home_score) {
            return $this->awayTeam;
        }

        return null; // Draw
    }
}

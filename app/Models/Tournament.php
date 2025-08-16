<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'prize_pool',
        'status',
        'start_date',
        'end_date',
        'max_teams',
        'venue',
        'logo',
        'organizer_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $appends = [
        'type',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'tournament_teams')
            ->withPivot(['status', 'points', 'goals_for', 'goals_against', 'goal_difference', 'matches_played', 'wins', 'draws', 'losses'])
            ->withTimestamps();
    }

    public function matches()
    {
        return $this->hasMany(MatchModel::class);
    }

    public function committees()
    {
        return $this->hasMany(Committee::class);
    }

    public function getStandingsAttribute()
    {
        return $this->teams()->orderBy('pivot_points', 'desc')
            ->orderBy('pivot_goal_difference', 'desc')
            ->orderBy('pivot_goals_for', 'desc')
            ->get();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function getTypeAttribute()
    {
        $ranges = [
            16 => 'Knockout',
            32 => 'Group & Knockout',
        ];

        foreach ($ranges as $limit => $label) {
            if ($this->max_teams <= $limit) {
                return $label;
            }
        }

        return 'League';
    }
}

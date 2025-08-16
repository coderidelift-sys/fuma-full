<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo',
        'city',
        'country',
        'manager_name',
        'manager_phone',
        'manager_email',
        'rating',
        'trophies_count',
        'manager_id'
    ];

    protected $casts = [
        'rating' => 'decimal:2',
    ];

    protected $appends = [
        'players_count',
    ];

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

    public function getPlayersCountAttribute()
    {
        return $this->players()->count();
    }
}

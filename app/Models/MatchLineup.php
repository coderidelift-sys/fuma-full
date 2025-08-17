<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchLineup extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'team_id',
        'player_id',
        'type',
        'jersey_number',
        'position',
        'substitution_minute',
        'substitution_type',
        'is_captain',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_captain' => 'boolean',
        'jersey_number' => 'integer',
        'substitution_minute' => 'integer',
    ];

    public function match()
    {
        return $this->belongsTo(MatchModel::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function scopeStartingXI($query)
    {
        return $query->where('type', 'starting_xi');
    }

    public function scopeSubstitutes($query)
    {
        return $query->where('type', 'substitute');
    }

    public function scopeBench($query)
    {
        return $query->where('type', 'bench');
    }

    public function scopeByTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    public function scopeCaptains($query)
    {
        return $query->where('is_captain', true);
    }

    public function getFormationPositionAttribute()
    {
        return $this->metadata['formation_position'] ?? null;
    }

    public function getSubstitutionInfoAttribute()
    {
        if ($this->substitution_minute && $this->substitution_type) {
            return "{$this->substitution_type} {$this->substitution_minute}'";
        }
        return null;
    }
}

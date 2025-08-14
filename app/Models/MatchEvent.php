<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'player_id',
        'type',
        'minute',
        'description',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function match()
    {
        return $this->belongsTo(MatchModel::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function scopeGoals($query)
    {
        return $query->where('type', 'goal');
    }

    public function scopeCards($query)
    {
        return $query->whereIn('type', ['yellow_card', 'red_card']);
    }

    public function scopeByMinute($query, $minute)
    {
        return $query->where('minute', $minute);
    }
}

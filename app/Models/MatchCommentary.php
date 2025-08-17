<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchCommentary extends Model
{
    use HasFactory;

    protected $table = 'match_commentary';

    protected $fillable = [
        'match_id',
        'user_id',
        'user_role',
        'minute',
        'commentary_type',
        'description',
        'is_important'
    ];

    protected $casts = [
        'is_important' => 'boolean',
        'minute' => 'integer'
    ];

    // Relationships
    public function match(): BelongsTo
    {
        return $this->belongsTo(MatchModel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByMatch($query, $matchId)
    {
        return $query->where('match_id', $matchId);
    }

    public function scopeByMinute($query, $minute)
    {
        return $query->where('minute', $minute);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('commentary_type', $type);
    }

    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    public function scopeOrderedByMinute($query)
    {
        return $query->orderBy('minute', 'asc')->orderBy('created_at', 'asc');
    }

    // Helper methods
    public function getFormattedMinuteAttribute(): string
    {
        return $this->minute . "'";
    }

    public function getCommentaryTypeLabelAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->commentary_type));
    }

    public function getUserRoleLabelAttribute(): string
    {
        $labels = [
            'referee' => 'Referee',
            'commentator' => 'Commentator',
            'admin' => 'Administrator',
            'match_official' => 'Match Official'
        ];

        return $labels[$this->user_role] ?? ucfirst($this->user_role);
    }

    public function getCommentaryIconAttribute(): string
    {
        $icons = [
            'general' => 'fas fa-comment',
            'tactical' => 'fas fa-chess',
            'incident' => 'fas fa-exclamation-triangle',
            'highlight' => 'fas fa-star',
            'warning' => 'fas fa-exclamation-circle'
        ];

        return $icons[$this->commentary_type] ?? 'fas fa-comment';
    }

    public function getCommentaryColorAttribute(): string
    {
        $colors = [
            'general' => 'text-muted',
            'tactical' => 'text-info',
            'incident' => 'text-warning',
            'highlight' => 'text-success',
            'warning' => 'text-danger'
        ];

        return $colors[$this->commentary_type] ?? 'text-muted';
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchScoreUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $matchId;
    public int $homeScore;
    public int $awayScore;

    public function __construct(int $matchId, int $homeScore, int $awayScore)
    {
        $this->matchId = $matchId;
        $this->homeScore = $homeScore;
        $this->awayScore = $awayScore;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('matches'),
            new Channel('match.' . $this->matchId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'match.score.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'match_id' => $this->matchId,
            'home_score' => $this->homeScore,
            'away_score' => $this->awayScore,
        ];
    }
}



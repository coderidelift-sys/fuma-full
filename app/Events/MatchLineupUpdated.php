<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchLineupUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $matchId;
    public int $teamId;

    public function __construct(int $matchId, int $teamId)
    {
        $this->matchId = $matchId;
        $this->teamId = $teamId;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('match.' . $this->matchId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'match.lineup.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'match_id' => $this->matchId,
            'team_id' => $this->teamId,
        ];
    }
}



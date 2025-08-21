<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $matchId;
    public string $status;

    public function __construct(int $matchId, string $status)
    {
        $this->matchId = $matchId;
        $this->status = $status;
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
        return 'match.status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'match_id' => $this->matchId,
            'status' => $this->status,
        ];
    }
}



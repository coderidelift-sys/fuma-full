<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchMinuteUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $matchId;
    public int $currentMinute;

    public function __construct(int $matchId, int $currentMinute)
    {
        $this->matchId = $matchId;
        $this->currentMinute = $currentMinute;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('match.' . $this->matchId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'match.minute.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'match_id' => $this->matchId,
            'current_minute' => $this->currentMinute,
        ];
    }
}



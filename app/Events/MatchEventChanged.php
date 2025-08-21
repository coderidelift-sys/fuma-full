<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchEventChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $matchId;
    public string $action; // created|updated|deleted
    public array $payload;

    public function __construct(int $matchId, string $action, array $payload)
    {
        $this->matchId = $matchId;
        $this->action = $action;
        $this->payload = $payload;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('match.' . $this->matchId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'match.event.' . $this->action;
    }

    public function broadcastWith(): array
    {
        return $this->payload + ['match_id' => $this->matchId];
    }
}



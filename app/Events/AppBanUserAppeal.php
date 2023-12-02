<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppBanUserAppeal
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    private int $appeal_counter;
    private string $appeal_card;

    /**
     * Create a new event instance.
     */
    public function __construct(int $appeal_counter, string $appeal_card)
    {
        $this->appeal_counter = $appeal_counter;
        $this->appeal_card = $appeal_card;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin'),
        ];
    }

    /**
     * Give a name  to the notification to ease the mechanism of processing it on the frontend.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'admin-notification';
    }
}

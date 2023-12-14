<?php

namespace App\Events;

use App\Models\AppBanAppeal;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Appeal implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public string $author;
    public array $message;

    /**
     * @$reaction_type String value of one of the enum values of the ReactionType enum
     * Create a new event instance.
     */
    public function __construct(string $author, User $user, AppBanAppeal $appeal)
    {
        $this->author = $author;

        $this->message = [
            'user' => $user,
            'appeal_card' => view('partials.user_card', ['appeal' => $appeal, 'user' => $appeal->appban->user, 'adminView' => true, 'appealView' => true])->render()
        ];
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
        return 'appeal-notification';
    }
}

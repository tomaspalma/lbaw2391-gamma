<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Reaction implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public string $author;
    public array $message;

    /**
    * @$reaction_type String value of one of the enum values of the ReactionType enum
     * Create a new event instance.
     */
    public function __construct(string $author, User $user, string $reaction_type, ?int $post_id, ?int $comment_id)
    {
        $this->author = $author;
        $this->message = [
            'user' => $user, 
            'reaction_type' => $reaction_type, 
            'post_id' => $post_id,
            'comment_id' => $comment_id
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
            new PrivateChannel('user.'.$this->author),
        ];
    }
    
    /**
     * Give a name  to the notification to ease the mechanism of processing it on the frontend.
     *
     * @return string
     */
    public function broadcastAs() 
    {
        return 'reaction-notification';
    }
}

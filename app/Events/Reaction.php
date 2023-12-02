<?php

namespace App\Events;

use App\Http\Controllers\DateController;
use App\Models\Reaction as ModelsReaction;
use App\Models\ReactionNot;
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
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public string $author;
    public array $message;

    /**
     * @$reaction_type String value of one of the enum values of the ReactionType enum
     * Create a new event instance.
     */
    public function __construct(string $author, User $user, string $reaction_type, ?int $post_id, ?int $comment_id)
    {
        $this->author = $author;

        $reaction = ModelsReaction::where('author', $user->id)
            ->where('type', $reaction_type)
            ->where(function ($query) use ($post_id, $comment_id) {
                $query->where(function ($subquery) use ($post_id) {
                    $subquery->where('post_id', $post_id)
                        ->whereNull('comment_id');
                })
                    ->orWhere(function ($subquery) use ($comment_id) {
                        $subquery->where('comment_id', $comment_id)
                            ->whereNull('post_id');
                    });
            })
            ->get();

        $reaction_not = ReactionNot::where('reaction_id', $reaction[0]->id)->get();
        $reaction_not_view = view('partials.notifications.reactions_notification', [
            'checkUser' => $user,
            'notification' => $reaction_not[0],
            'date' => DateController::format_date($reaction_not[0]->date)
        ])->render();

        $this->message = [
            'user' => $user,
            'reaction_type' => $reaction_type,
            'post_id' => $post_id,
            'comment_id' => $comment_id,
            'reaction_not_view' => $reaction_not_view,
            'c' => $reaction_not[0]
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
            new PrivateChannel('user.' . $this->author),
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

<?php

namespace App\Events;

use App\Http\Controllers\DateController;
use App\Models\Comment as ModelsComment;
use App\Models\CommentNot;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Comment implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public string $post_author;
    public array $message;

    /**
     * Create a new event instance.
     */
    public function __construct(string $post_author, User $user, ModelsComment $comment)
    {
        $this->post_author = $post_author;

        $comment_not = CommentNot::where('comment_id', $comment->id)->get();

        $comment_not_view = view('partials.notifications.comments_notification', [
            'checkUser' => $user,
            'notification' => $comment_not[0],
            'date' => DateController::format_date($comment_not[0]->date)
        ])->render();

        $this->message = [
            'user' => $user,
            'post_author' => $post_author,
            'comment_not_view' => $comment_not_view
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
            new PrivateChannel('user.' . $this->post_author),
        ];
    }

    public function broadcastAs(): string
    {
        return 'comment-notification';
    }
}

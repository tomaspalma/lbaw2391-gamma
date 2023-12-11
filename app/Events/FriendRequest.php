<?php

namespace App\Events;

use App\Http\Controllers\DateController;
use App\Models\FriendRequestNot;
use App\Models\FriendRequest as FriendRequestModel;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $author;
    public array $message;

    /**
     * Create a new event instance.
     */
    public function __construct(User $receiver, User $sender, ?bool $is_accepted)
    {

        $friend_request = FriendRequestModel::where('user_id', $sender->id)->where('friend_id', $receiver->id)->where('is_accepted', $is_accepted)->get();

        if ($is_accepted === null) {
            $this->author = $receiver->username;
            $user = $sender;
        } else {
            $this->author = $sender->username;
            $user = $receiver;
        }


        $friend_request_not = FriendRequestNot::where('friend_request', $friend_request[0]->id)->where('is_accepted', $is_accepted)->get();
        $friend_request_not_view = view('partials.notifications.friend_request_notification', [
            'checkUser' => $this->author,
            'user' => $user,
            'is_accepted' => $is_accepted,
            'notification' => $friend_request_not[0],
            'date' => DateController::format_date($friend_request_not[0]->date)
        ])->render();

        $this->message = [
            'user' => $user,
            'friend_request_not_view' => $friend_request_not_view
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
        return 'friend-request-notification';
    }
}

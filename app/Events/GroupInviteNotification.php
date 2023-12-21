<?php

namespace App\Events;
use App\Http\Controllers\DateController;
use App\Models\GroupInvite;
use App\Models\GroupInviteNot;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupInviteNotification implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public int $user_id;
    public array $message;

    private GroupInvite $groupInvite;

    /**
     * Create a new event instance.
     */
    public function __construct(int $user_id, GroupInvite $groupInvite)
    {
        $this->user_id = $user_id;
        $this->groupInvite = $groupInvite;
        $user = User::findOrFail($user_id);

        //$group_request_not = GroupInviteNot::where('group_request_id', $groupInvite->id)->get();

        /*
        $group_request_not_view = view('partials.notifications.group_requests_notification', [
            'notification' => $group_request_not[0],
            'date' => DateController::format_date($group_request_not[0]->date)
        ])->render();
        */

        $this->message = [
            'user' => $user,
            'user_id' => $user_id
            //'group_request_not_view' => $group_request_not_view
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
            new PrivateChannel('user.' . $this->groupInvite->user->username),
        ];
    }
    

    public function broadcastAs(): string
    {
        return 'group-invite-notification';
    }
}

<?php

namespace App\Events;
use App\Http\Controllers\DateController;
use App\Models\GroupRequest;
use App\Models\GroupRequestNot;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupRequestNotification implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public int $user_id;
    public array $message;

    private GroupRequest $groupRequest;

    /**
     * Create a new event instance.
     */
    public function __construct(int $user_id, GroupRequest $groupRequest, bool $acceptance)
    {
        $this->user_id = $user_id;
        $this->groupRequest = $groupRequest;
        $user = User::findOrFail($user_id);

        $group_request_not = GroupRequestNot::where('group_request_id', $groupRequest->id)->get();

        $group_request_not[0]->is_acceptance = $acceptance;

        $group_request_not_view = view('partials.notifications.group_requests_notification', [
            'notification' => $group_request_not[0],
            'date' => DateController::format_date($group_request_not[0]->date)
        ])->render();

        $this->message = [
            'user' => $user,
            'user_id' => $user_id,
            'group_request_not_view' => $group_request_not_view
        ];

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {   
        if (!$this->groupRequest->is_accepted){
            $channels = [];
            $owners = $this->groupRequest->group->owners();
            foreach ($owners->get() as $owner) {
                array_push($channels, new PrivateChannel('user.' . $owner->username));
            }
    
            return $channels;
        }
        
        return [
            new PrivateChannel('user.' . $this->groupRequest->user->username),
        ];
    }
    

    public function broadcastAs(): string
    {
        return 'group-request-notification';
    }
}

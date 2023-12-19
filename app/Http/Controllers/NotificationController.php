<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function show_notifications(Request $request, string $filter = null)
    {
        $notifications = null;
        if ($filter === 'reactions') {
            $notifications = $request->user()->reaction_notifications();
        } elseif ($filter === 'comments') {
            $notifications = $request->user()->comment_notifications();
        } elseif ($filter === 'friend-requests') {
            $notifications = $request->user()->friend_request_notifications();
        } elseif ($filter == 'group-requests'){
            $notifications = $request->user()->group_request_notifications();
        } elseif ($filter == 'group-requests-acceptance'){
            $notifications = $request->user()->group_request_accept_notifications();
        } else {
            $notifications = $request->user()->normal_notifications();
        }

        if ($request->is("api*")) {
            return view('partials.notifications.notification_card_holder', ['notifications' => $notifications])->render();
        } else {
            return view('pages.notifications', [
                'notifications' => $notifications
            ]);
        }
    }
}

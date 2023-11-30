<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function show_notifications(Request $request, string $filter = null) {
        $notifications = null;
        if($filter === 'reactions') {
            $notifications = $request->user()->reaction_notifications();
        } else if($filter === 'comments') {
            $notifications = $request->user()->comment_notifications();
        } else {
            $notifications = $request->user()->normal_notifications();
        }
        
        return view('pages.notifications', [
            'notifications' => $notifications
        ])->render();
    }
}

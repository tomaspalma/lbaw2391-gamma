<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function show_notifications(Request $request) {
        return view('pages.notifications', ['notifications' => $request->user()->normal_notifications()]);
    }
}

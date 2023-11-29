<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function show_user_appeals(Request $request) {
        return view('pages.show_user_appeals');
    }

    public function show_admin_user(Request $request)
    {
        $users = User::all();

        return view('pages.admin', [
            'users' => $users
        ]);
    }

    /**
     *
     */
    public function show_create_user()
    {
        return view('pages.admin_create_user');
    }
}

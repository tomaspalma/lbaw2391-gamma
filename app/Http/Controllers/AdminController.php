<?php

namespace App\Http\Controllers;

use App\Models\AppBanAppeal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function show_user_appeals(Request $request) {
        $appeals = AppBanAppeal::paginate(15);

        return view('pages.admin_user_appeals', ['appeals' => $appeals]);
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

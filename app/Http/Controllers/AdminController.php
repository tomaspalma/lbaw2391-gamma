<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function show_admin_user()
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

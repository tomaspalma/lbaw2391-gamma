<?php

namespace App\Http\Controllers;


use Illuminate\View\View;

use App\Models\User;

class UserController extends Controller
{
    public function show(string $username): View
    {
        $user = User::where('username', $username)->firstOrFail();

        //$this->authorize('show', $user);

        return view('pages.profile', [
            'user' => $user
        ]);
    }

}

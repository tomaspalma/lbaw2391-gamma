<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\View\View;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Display a login form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        /*
        $request->validate([
            'username' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'academic_status' => 'required|string|max:10',
            'display_name'=> 'required|string|max:10',
            'is_private' => 'required',
            'role' => 'required'
        ]);
        */

        $last_id = DB::select('SELECT id FROM users ORDER BY id DESC LIMIT 1')[0]->id;
        $new_id = $last_id + 1;

        User::create([
            'id' => $new_id,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'academic_status' => $request->academic_status,
            'display_name' => $request->display_name,
            'is_private' => ($request->is_private == 'yes'),
            'role'=> $request->role
        ]);
        

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('cards')
            ->withSuccess('You have successfully registered & logged in!');
    }
}
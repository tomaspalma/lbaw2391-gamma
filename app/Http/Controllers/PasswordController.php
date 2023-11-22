<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

/**
 * PasswordController is used to actions related to resetting the password of the user
 */
class PasswordController extends Controller
{
    public function show_forgot_password()
    {
        return view('auth.forgot_password');
    }

    public function send_forgot_password_request(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $reset_password_status = Password::sendResetLink($request->only('email'));

        return $reset_password_status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($reset_password_status)])
            : back()->withErrors(['email' => __($reset_password_status)]);
    }

    public function show_reset_password(string $token)
    {
        return view('auth.reset_password', ['token' => $token]);
    }

    public function reset_password(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed'
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function (User $user, string $password) {
            UserController::reset_password($user, $password);
        });

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}

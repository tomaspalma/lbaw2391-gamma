<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    /**
     * Marks a user as verified by email
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect('/feed');
    }

    /**
     * Resendes an email with the verification link for the user that is logged in
     */
    public function resend_verification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'A new verification link was sent!');
    }

    public function show_email_verification_notice()
    {
        return view('pages.email_validation_notice');
    }
}

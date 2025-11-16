<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Dev-friendly: do not send email. Show informational message.
        Session::flash('status', 'Reset emails are not configured on this dev server. '
            . 'To reset a password, open the reset URL (e.g. /password/reset/TOKEN?email=you@example.com) and set a new password.');

        return back();
    }
}

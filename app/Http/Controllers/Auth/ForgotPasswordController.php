<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        // simple view or redirect to login with message for now
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request)
    {
        // For demo: show message and redirect back.
        return back()->with('status', 'If this email exists we have sent a reset link (demo).');
    }
}

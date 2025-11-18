<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        // Demo stub: accept and redirect to login
        // Real implementation should validate token & update user's password
        return redirect()->route('login')->with('success', 'Password reset (demo).');
    }
}

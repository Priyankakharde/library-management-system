<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Public landing page
     */
    public function welcome()
    {
        return view('welcome');
    }

    /**
     * Home for authenticated users (alias to dashboard or simple home)
     */
    public function index()
    {
        return redirect()->route('dashboard');
    }
}

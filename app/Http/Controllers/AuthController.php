<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function index()
    {
        if (auth()->check()) {
            return redirect('dashboard');
        }

        return view('login');
    }

    function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with(['alertMessage' => 'You have logged out!']);
    }

    function login(Request $request)
    {
        $form_fields = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($form_fields)) {
            return redirect('dashboard');
        } else {
            return back()->with('alertError', 'Invalid user name or password.');
        }
    }
}

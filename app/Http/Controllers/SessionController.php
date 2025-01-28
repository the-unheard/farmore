<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store()
    {
        // validate
        $attributes = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        // attempt to login
        if (! Auth::attempt($attributes)) {
            throw ValidationException::withMessages([
                'email' => 'Sorry, those credentials do not match.'
            ]);
        }

        // regenerate the session token
        request()->session()->regenerate();

        // redirect
        return redirect('/dashboard');
    }

    public function destroy()
    {
        Auth::logout();

        return redirect('/dashboard');
    }
}

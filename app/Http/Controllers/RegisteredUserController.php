<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{

    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'username' => [
                'required',
                'regex:/^[a-zA-Z0-9._-]+$/', // Allow letters, numbers, dot, dash, underscore
                'min:1',
                'max:20',
                'unique:users,username'
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],
            'password' => [
                'required',
                Password::min(6),
                'confirmed' // Requires a matching password_confirmation field
            ]
        ]);
        $user = User::create($attributes);
        Auth::login($user);
        return redirect('/dashboard')->with('success', 'Welcome, ' . $user->username . '!');
    }
}

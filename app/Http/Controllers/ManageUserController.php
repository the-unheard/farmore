<?php

namespace App\Http\Controllers;

use App\Models\User;

class ManageUserController extends Controller
{

    public function index()
    {
        return view('manage-users.index', [
            'users' => User::select('id', 'username', 'email', 'created_at')->with('roles:id,name')->orderBy('id', 'asc')->paginate(10)
        ]);
    }

    public function create()
    {

    }

    public function store()
    {

    }

    public function show($id)
    {
        $user = User::with('roles:id,name')->find($id);

        if (!$user) {
            return redirect()->route('manage-users.index')->with('error', 'User not found.');
        }

        return view('manage-users.show', ['user' => $user]);
    }


    public function edit(User $user)
    {

    }

    public function update(User $user)
    {

    }

    public function destroy(User $user)
    {

    }

}

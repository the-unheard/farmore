<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;

class ManageUserController extends Controller
{

    public function index()
    {
        return view('manage-users.index', [
            'users' => User::select('id', 'username', 'email', 'created_at')->with('roles:id,name')->orderBy('id', 'asc')->paginate(10)
        ]);
    }

    public function show($id)
    {
        $user = User::with('roles:id,name')->find($id);

        if (!$user) {
            return redirect()->route('manage-users.index')->with('error', 'User not found.');
        }

        return view('manage-users.show', ['user' => $user]);
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);


        return view('manage-users.edit', [
            'user' => $user,
        ]);
    }

    public function update($id)
    {
        $validatedData = $this->validateInput();

        $user = User::findOrFail($id);
        $user->update($validatedData);

        return redirect('/manage-users/' . $id)->with('success', 'User record updated successfully.');
    }

    public function destroy($id)
    {

        $user = User::findOrFail($id);

        if ($user->id === 1) {
            return redirect('/manage-users')->with('Error', 'You don\'t have permission to delete this.');
        }

        $user->delete();
        return redirect('/manage-users')->with('success', 'User deleted successfully.');
    }

    private function validateInput(): array
    {
        $validatedData = request()->validate([
            'username' => [
                'required',
                'regex:/^[a-zA-Z0-9._-]+$/', // Allows letters, numbers, dots, dashes, and underscores
                'min:1',
                'max:20',
                'unique:users,username,' . request()->route('user')
            ],
        ]);

        return $validatedData;
    }

}

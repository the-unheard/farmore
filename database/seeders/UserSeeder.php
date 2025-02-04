<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a role if it doesn't exist
        Role::firstOrCreate(['name' => 'admin']);

        // Create an admin user or assign the role to an existing user
        $user = User::firstOrCreate([
            'email' => 'admin@farmore.com',
        ], [
            'username' => 'Admin',
            'password' => Hash::make('asdasd'),
        ]);

        // Assign the "admin" role to this user
        $user->assignRole('admin');
    }
}

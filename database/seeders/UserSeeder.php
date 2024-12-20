<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'Admin',
            'name' => 'Admin',
            'password' => '1234567890',
            'email' => 'admin@gmail.com',
            'no_hp' => '09876543',
            'jenisUser_id' => 1,
            'status_user' => 'Active',
        ]);
        User::create([
            'username' => 'Guest',
            'name' => 'Guest',
            'password' => '1234567890',
            'email' => 'guest@gmail.com',
            'no_hp' => '09876543',
            'jenisUser_id' => 2,
            'status_user' => 'Active',
        ]);
        User::create([
            'username' => 'it',
            'name' => 'it',
            'password' => '1234567890',
            'email' => 'it@gmail.com',
            'no_hp' => '09876543',
            'jenisUser_id' => 3,
            'status_user' => 'Active',
        ]);
    }
}

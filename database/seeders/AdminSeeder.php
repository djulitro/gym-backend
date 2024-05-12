<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create User
        $user = User::create([
            'email' => 'admin@admin.cl',
            'password' => Hash::make('eadgym459.,'),
            'confirm_email' => 1,
        ]);

        // Create Admin

        Admin::create([
            'user_id' => $user->id,
            'name' => 'Admin',
            'last_name' => 'Admin',
            'status' => 1,
        ]);
    }
}

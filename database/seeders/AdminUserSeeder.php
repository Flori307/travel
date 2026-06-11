<?php
// database/seeders/AdminUserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        Client::create([
            'first_name' => 'Admin',
            'last_name' => 'System',
            'email' => 'admin@traveldream.com',
            'phone' => '+7 (999) 999-99-99',
            'password' => Hash::make('admin123'),
            'registration_date' => now(),
            'role' => 'admin',
            'is_blocked' => false,
        ]);
        
        Client::create([
            'first_name' => 'Moderator',
            'last_name' => 'User',
            'email' => 'moderator@traveldream.com',
            'phone' => '+7 (999) 888-88-88',
            'password' => Hash::make('moderator123'),
            'registration_date' => now(),
            'role' => 'moderator',
            'is_blocked' => false,
        ]);
    }
}
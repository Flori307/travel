<?php
// database/seeders/ClientSeeder.php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'first_name' => 'Артем',
                'last_name' => 'Готлиб',
                'email' => 'artemeshesh@gmail.com',
                'phone' => '+79822872567',
                'registration_date' => '2025-01-15',
                'password' => Hash::make('password123'), // Добавляем пароль
            ],
            [
                'first_name' => 'Александр',
                'last_name' => 'Александр228',
                'email' => 'meshupesh@gmail.com',
                'phone' => '+79835575657',
                'registration_date' => '2025-02-01',
                'password' => Hash::make('password123'),
            ],
            [
                'first_name' => 'Рустам',
                'last_name' => 'Рустик',
                'email' => 'kakashki@gmail.com',
                'phone' => '+89085678966',
                'registration_date' => '2025-01-20',
                'password' => Hash::make('password123'),
            ],
            [
                'first_name' => 'Sophie',
                'last_name' => 'Софа',
                'email' => 'mastevaya@gmail.com',
                'phone' => '+89087697966',
                'registration_date' => '2025-02-10',
                'password' => Hash::make('password123'),
            ]
        ];

        foreach ($clients as $client) {
            Client::firstOrCreate(
                ['email' => $client['email']],
                $client
            );
        }
    }
}
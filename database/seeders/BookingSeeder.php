<?php
// database/seeders/BookingSeeder.php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Tour;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $clients = Client::all();
        $tours = Tour::all();

        foreach ($clients as $client) {
            // Создаем 1-3 бронирования для каждого клиента
            $bookingCount = rand(1, 3);
            
            for ($i = 0; $i < $bookingCount; $i++) {
                $tour = $tours->random();
                $status = $this->getRandomStatus();
                
                Booking::create([
                    'client_id' => $client->client_id,
                    'tour_id' => $tour->tour_id,
                    'booking_date' => now()->subDays(rand(1, 90)),
                    'number_of_people' => rand(1, 4),
                    'status' => $status,
                    'total_price' => $tour->price * rand(1, 4),
                ]);
            }
        }
    }

    private function getRandomStatus(): string
    {
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        $weights = [1, 3, 4, 1]; // Веса для каждого статуса
        
        $random = rand(1, array_sum($weights));
        $current = 0;
        
        foreach ($statuses as $index => $status) {
            $current += $weights[$index];
            if ($random <= $current) {
                return $status;
            }
        }
        
        return 'confirmed';
    }
}
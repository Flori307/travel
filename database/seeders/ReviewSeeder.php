<?php
// database/seeders/ReviewSeeder.php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Client;
use App\Models\Tour;
use App\Models\Booking;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        // Берем клиентов у которых есть завершенные бронирования
        $completedBookings = Booking::where('status', 'completed')
            ->with(['client', 'tour'])
            ->get();

        foreach ($completedBookings as $booking) {
            // Создаем отзыв только если его еще нет
            $existingReview = Review::where('client_id', $booking->client_id)
                ->where('tour_id', $booking->tour_id)
                ->first();

            if (!$existingReview && rand(0, 1)) { // 50% chance
                Review::create([
                    'client_id' => $booking->client_id,
                    'tour_id' => $booking->tour_id,
                    'rating' => rand(4, 5),
                    'comment' => $this->getRandomComment(),
                ]);
            }
        }
    }

    private function getRandomComment(): string
    {
        $comments = [
            'Отличный тур! Все очень понравилось, организация на высшем уровне.',
            'Прекрасный отель и интересная программа. Рекомендую!',
            'Спасибо за незабываемые впечатления. Гиды профессионалы своего дела.',
            'Все было супер! Обязательно поеду еще раз с этой компанией.',
            'Хорошее соотношение цены и качества. Море эмоций и красивых фото.',
            'Тур превзошел все ожидания. Отдельное спасибо гиду за интересные истории.',
            'Комфортный перелет, уютный отель, насыщенная программа. Что еще нужно для отдыха?',
            'Понравилось абсолютно все! Уже планируем следующую поездку.',
            'Профессиональная организация, внимательное отношение к туристам.',
            'Отдых получился именно таким, как я хотел. Спасибо команде TravelDream!'
        ];

        return $comments[array_rand($comments)];
    }
}
<?php
// database/seeders/HotelSeeder.php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = [
            [
                'name' => 'Grand Hotel Europe',
                'description' => 'Роскошный 5-звездочный отель в историческом центре Рима. Великолепный вид на достопримечательности, элегантные номера с современными удобствами и исключительный сервис. Идеальное место для романтического отпуска или деловой поездки.',
                'amenities' => json_encode([
                    'Бесплатный Wi-Fi', 'Бассейн', 'Спа-центр', 'Фитнес-зал', 'Ресторан', 
                    'Бар', 'Консьерж-сервис', 'Трансфер', 'Парковка', 'Химчистка'
                ]),
                'address' => '123 Main Street',
                'city' => 'Rome',
                'country' => 'Italy',
                'star_rating' => 5,
                'contact_phone' => '+39 06 1234567',
                'image_url' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'room_count' => 120,
                'latitude' => 41.9028,
                'longitude' => 12.4964,
                'website' => 'https://grand-hotel-europe-rome.com',
                'email' => 'info@grand-hotel-europe-rome.com',
            ],
            [
                'name' => 'Sea View Resort',
                'description' => 'Современный курортный комплекс на побережье Барселоны с собственным пляжем. Просторные номера с видом на море, несколько бассейнов, спа-центр и разнообразные рестораны. Отличный выбор для семейного отдыха.',
                'amenities' => json_encode([
                    'Бесплатный Wi-Fi', 'Частный пляж', '3 бассейна', 'Спа-центр', 'Фитнес-зал',
                    '4 ресторана', 'Бар у бассейна', 'Детский клуб', 'Теннисный корт', 'Парковка'
                ]),
                'address' => '456 Beach Road',
                'city' => 'Barcelona',
                'country' => 'Spain',
                'star_rating' => 4,
                'contact_phone' => '+34 93 7654321',
                'image_url' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                'check_in_time' => '15:00',
                'check_out_time' => '11:00',
                'room_count' => 200,
                'latitude' => 41.3851,
                'longitude' => 2.1734,
                'website' => 'https://sea-view-resort-barcelona.com',
                'email' => 'reservations@sea-view-resort-barcelona.com',
            ],
            [
                'name' => 'Mountain Lodge',
                'description' => 'Уютный горный отель в швейцарских Альпах с потрясающим видом на горные вершины. Традиционный швейцарский дизайн, камин в лобби, сауна и непосредственная близость к горнолыжным трассам.',
                'amenities' => json_encode([
                    'Бесплатный Wi-Fi', 'Сауна', 'Камин', 'Ресторан', 'Бар',
                    'Прокат лыж', 'Трансфер к подъемникам', 'Парковка', 'Химчистка', 'Сейф'
                ]),
                'address' => '789 Alpine Street',
                'city' => 'Interlaken',
                'country' => 'Switzerland',
                'star_rating' => 4,
                'contact_phone' => '+41 33 9876543',
                'image_url' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                'check_in_time' => '14:00',
                'check_out_time' => '11:00',
                'room_count' => 80,
                'latitude' => 46.6863,
                'longitude' => 7.8632,
                'website' => 'https://mountain-lodge-interlaken.com',
                'email' => 'hello@mountain-lodge-interlaken.com',
            ],
            [
                'name' => 'Paris Luxury Hotel',
                'description' => 'Бутик-отель в самом сердце Парижа на Елисейских полях. Элегантные номера в стиле ар-деко, панорамный вид на город, мишленовский ресторан и эксклюзивный спа-центр.',
                'amenities' => json_encode([
                    'Бесплатный Wi-Fi', 'Спа-центр', 'Ресторан Мишлен', 'Бар на крыше', 'Фитнес-зал',
                    'Консьерж-сервис', 'Лимузин-сервис', 'Химчистка', 'Сейф', 'Бизнес-центр'
                ]),
                'address' => '321 Champs-Élysées',
                'city' => 'Paris',
                'country' => 'France',
                'star_rating' => 5,
                'contact_phone' => '+33 1 45678901',
                'image_url' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                'check_in_time' => '16:00',
                'check_out_time' => '12:00',
                'room_count' => 60,
                'latitude' => 48.8738,
                'longitude' => 2.2950,
                'website' => 'https://paris-luxury-hotel.com',
                'email' => 'reservations@paris-luxury-hotel.com',
            ],
            [
                'name' => 'Aegean Paradise',
                'description' => 'Эксклюзивный курорт на скалах Санторини с невероятным видом на кальдеру. Белоснежные номера с частными бассейнами, ресторан с панорамным видом и私人ный пляж.',
                'amenities' => json_encode([
                    'Бесплатный Wi-Fi', 'Частные бассейны', 'Спа-центр', 'Ресторан', 'Бар',
                    'Частный пляж', 'Трансфер', 'Парковка', 'Химчистка', 'Экскурсионное бюро'
                ]),
                'address' => '654 Santorini Street',
                'city' => 'Santorini',
                'country' => 'Greece',
                'star_rating' => 5,
                'contact_phone' => '+30 22860 12345',
                'image_url' => 'https://images.unsplash.com/photo-1613395877344-13d4a8e0d49e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                'check_in_time' => '14:00',
                'check_out_time' => '11:00',
                'room_count' => 40,
                'latitude' => 36.3932,
                'longitude' => 25.4615,
                'website' => 'https://aegean-paradise-santorini.com',
                'email' => 'info@aegean-paradise-santorini.com',
            ],
            [
                'name' => 'Bodrum Resort & Spa',
                'description' => 'Роскошный курортный комплекс на побережье Эгейского моря. Современные номера с видом на море, огромный спа-центр, несколько бассейнов и разнообразные рестораны с местной и международной кухней.',
                'amenities' => json_encode([
                    'Бесплатный Wi-Fi', 'Спа-центр', '3 бассейна', 'Фитнес-зал', '4 ресторана',
                    'Частный пляж', 'Водные виды спорта', 'Детский клуб', 'Теннисный корт', 'Парковка'
                ]),
                'address' => '987 Beach Boulevard',
                'city' => 'Bodrum',
                'country' => 'Turkey',
                'star_rating' => 5,
                'contact_phone' => '+90 252 123 4567',
                'image_url' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'room_count' => 180,
                'latitude' => 37.0344,
                'longitude' => 27.4305,
                'website' => 'https://bodrum-resort-spa.com',
                'email' => 'bookings@bodrum-resort-spa.com',
            ],
        ];

        foreach ($hotels as $hotel) {
            // Используем updateOrCreate чтобы обновить существующие записи
            Hotel::updateOrCreate(
                [
                    'name' => $hotel['name'],
                    'city' => $hotel['city']
                ],
                $hotel
            );
        }
    }
}
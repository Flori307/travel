<?php
// database/seeders/TourSeeder.php

namespace Database\Seeders;

use App\Models\Tour;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    public function run(): void
    {
        $tours = [
            [
                'hotel_id' => 1,
                'title' => 'Романтическая Италия',
                'description' => 'Классика: Прогуляйтесь по античным руинам Рима, а затем потеряйтесь на романтичных каналах Венеции. От Колизея до гондол — всё самое главное за несколько дней.

Для гурманов: Исследуйте художественные сокровища Флоренции и отправьтесь в Неаполь, чтобы попробовать настоящую пиццу. Искусство для души и еда для сердца.',
                'destination_country' => 'Italy',
                'destination_city' => 'Rome',
                'start_date' => '2025-06-01',
                'end_date' => '2025-06-08',
                'price' => 89999.00,
                'available_spots' => 20,
                'image_url' => 'https://www.studyitalian.ru/uploads/server/awUBqpEUsGMpgkRW.jpg',
            ],
            [
                'hotel_id' => 2,
                'title' => 'Испанское побережье',
                'description' => 'Для отдыха и вечеринок: Проведите неделю на Коста-дель-Соль, загорая на золотых пляжах, а ночи — в барах Марбельи. Идеально для тех, кто хочет совместить релакс и яркую ночную жизнь.

Для культуры и драмы: Исследуйте скалистые бухты Коста-Бравы, посетите старый город Жироны, а затем насладитесь архитектурой Гауди в Барселоне. Этот тур сочетает пляжный отдых с погружением в искусство и историю.',
                'destination_country' => 'Spain',
                'destination_city' => 'Barcelona',
                'start_date' => '2025-07-15',
                'end_date' => '2025-07-22',
                'price' => 75500.00,
                'available_spots' => 15,
                'image_url' => 'https://www.spain.info/export/sites/segtur/.content/imagenes/cabeceras-grandes/cataluna/calella-palafrugell-costa-brava_s208688674.jpg',
            ],
            [
                'hotel_id' => 3,
                'title' => 'Швейцарские Альпы',
                'description' => 'Классический отдых в Альпах: Прокатитесь на знаменитых поездах через самые живописные долины и поднимитесь на вершину Юнгфрауйох к вечным льдам. Затем поселитесь в уютном шале в Гриндельвальде, чтобы наслаждаться чистейшим воздухом и потрясающими видами на горные пики.

Активное летнее приключение: Исследуйте бирюзовые озера Люцерна и Интерлакена, а затем отправляйтесь в поход по маршрутам с панорамными видами на Маттерхорн. Этот тур подарит вам незабываемые впечатления от пеших прогулок и единения с природой.',
                'destination_country' => 'Switzerland',
                'destination_city' => 'Interlaken',
                'start_date' => '2025-08-10',
                'end_date' => '2025-08-17',
                'price' => 112000.00,
                'available_spots' => 12,
                'image_url' => 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/15/33/f9/1c/swiss-alps.jpg?w=1400&h=1400&s=1',
            ],
            [
                'hotel_id' => 4,
                'title' => 'Парижская сказка',
                'description' => 'Погрузитесь в атмосферу города мечты, поднявшись на Эйфелеву башню и прогулявшись по изысканным залам Версаля. Этот тур подарит вам романтику уличных кафе Монмартра и мировые шедевры в Лувре.',
                'destination_country' => 'France',
                'destination_city' => 'Paris',
                'start_date' => '2025-09-05',
                'end_date' => '2025-09-12',
                'price' => 95800.00,
                'available_spots' => 18,
                'image_url' => 'https://7d9e88a8-f178-4098-bea5-48d960920605.selcdn.net/70fafd98-4376-499a-9d68-93b808a7773b/-/format/webp/-/resize/1300x/',
            ],
            [
                'hotel_id' => 5,
                'title' => 'Греческие острова',
                'description' => 'Классика Киклад: Побродите по белоснежным улочкам Санторини с его синими куполами и насладитесь незабываемым закатом, а затем погрузитесь в бурную ночную жизнь и ветряные мельницы Миконоса.

История и природа: Совершите паломничество к античным руинам храма Аполлона на острове Наксос, а после исследуйте вулканические пейзажи и разноцветные пляжи Санторини.',
                'destination_country' => 'Greece',
                'destination_city' => 'Santorini',
                'start_date' => '2025-07-01',
                'end_date' => '2025-07-08',
                'price' => 82500.00,
                'available_spots' => 16,
                'image_url' => 'https://www.pitert.ru/sites/default/files/_0e079b0.jpg',
            ],
            [
                'hotel_id' => 6,
                'title' => 'Турецкая ривьера',
                'description' => 'Проведите неделю в Анталии, совмещая пляжный отдых на лазурном побережье с экскурсиями в древние города like Аспендос и каньон Кёпрюлю. Идеально для тех, кто хочет добавить к релаксу яркую ночную жизнь и богатую историю.',
                'destination_country' => 'Turkey',
                'destination_city' => 'Bodrum',
                'start_date' => '2025-08-20',
                'end_date' => '2025-08-27',
                'price' => 68900.00,
                'available_spots' => 25,
                'image_url' => 'https://profitrealestate.ru/image/adzzNAVtUZAf90tCVl.jpg',
            ],
        ];

        foreach ($tours as $tour) {
            Tour::firstOrCreate(
                [
                    'title' => $tour['title'],
                    'destination_city' => $tour['destination_city']
                ],
                $tour
            );
        }
    }
}
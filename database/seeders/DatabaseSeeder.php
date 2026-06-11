<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Hotel;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Полная очистка всех таблиц
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('bookings')->truncate();
        DB::table('reviews')->truncate();
        DB::table('tours')->truncate();
        DB::table('hotels')->truncate();
        DB::table('clients')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('🗑️ Таблицы очищены');

        // ========== 1. СОЗДАНИЕ ОТЕЛЕЙ (12 штук) ==========
        $hotelsData = [
            [
                'name' => 'Ритц-Карлтон Москва',
                'description' => 'Роскошный 5-звездочный отель в самом сердце Москвы с видом на Красную площадь. Номера класса люкс, спа-центр, рестораны высокой кухни.',
                'address' => 'ул. Тверская, 3',
                'city' => 'Москва',
                'country' => 'Россия',
                'star_rating' => 5,
                'contact_phone' => '+7 (495) 225-88-88',
                'email' => 'info@ritzcarlton.ru',
                'image_url' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/462354355.jpg',
                'room_count' => 334,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'website' => 'https://www.ritzcarlton.ru',
                'amenities' => json_encode(['Бесплатный Wi-Fi', 'Спа-центр', 'Бассейн', 'Фитнес-центр', 'Ресторан', 'Бар', 'Парковка']),
                'is_active' => true,
            ],
            [
                'name' => 'Арт-отель Санкт-Петербург',
                'description' => 'Уникальный бутик-отель в историческом центре Санкт-Петербурга. Интерьеры в стиле модерн, близость к Эрмитажу и Невскому проспекту.',
                'address' => 'Невский пр-т, 88',
                'city' => 'Санкт-Петербург',
                'country' => 'Россия',
                'star_rating' => 4,
                'contact_phone' => '+7 (812) 449-55-55',
                'email' => 'hello@arthotelspb.ru',
                'image_url' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/356789012.jpg',
                'room_count' => 120,
                'check_in_time' => '15:00',
                'check_out_time' => '11:00',
                'website' => 'https://www.arthotelspb.ru',
                'amenities' => json_encode(['Wi-Fi', 'Ресторан', 'Бар', 'Трансфер', 'Конференц-зал']),
                'is_active' => true,
            ],
            [
                'name' => 'Гранд Отель Сочи',
                'description' => 'Престижный отель на берегу Черного моря. Собственный пляж, аквапарк, теннисные корты. Идеально для семейного отдыха.',
                'address' => 'ул. Черноморская, 15',
                'city' => 'Сочи',
                'country' => 'Россия',
                'star_rating' => 5,
                'contact_phone' => '+7 (862) 255-33-33',
                'email' => 'info@grandsochi.ru',
                'image_url' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/298765432.jpg',
                'room_count' => 250,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'website' => 'https://www.grandsochi.ru',
                'amenities' => json_encode(['Wi-Fi', 'Бассейн', 'Спа', 'Фитнес', 'Ресторан', 'Бар', 'Пляж']),
                'is_active' => true,
            ],
            [
                'name' => 'Отель Казань Палас',
                'description' => 'Современный отель в центре Казани, рядом с Кремлем. Номера с панорамным видом на город.',
                'address' => 'ул. Баумана, 55',
                'city' => 'Казань',
                'country' => 'Россия',
                'star_rating' => 4,
                'contact_phone' => '+7 (843) 277-88-88',
                'email' => 'info@kazanpalace.ru',
                'image_url' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/412345678.jpg',
                'room_count' => 180,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'website' => 'https://www.kazanpalace.ru',
                'amenities' => json_encode(['Wi-Fi', 'Ресторан', 'Бар', 'Фитнес', 'Конференц-зал']),
                'is_active' => true,
            ],
            [
                'name' => 'Мрия Резорт & СПА',
                'description' => 'Пятизвездочный курорт на ЮБК. Собственный винный дом, аквапарк, медицинский центр, гольф-поле.',
                'address' => 'с. Оползневое, ул. Генерала Острякова, 9',
                'city' => 'Ялта',
                'country' => 'Россия',
                'star_rating' => 5,
                'contact_phone' => '+7 (3654) 55-11-11',
                'email' => 'welcome@mriyaresort.com',
                'image_url' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/378901234.jpg',
                'room_count' => 412,
                'check_in_time' => '15:00',
                'check_out_time' => '12:00',
                'website' => 'https://www.mriyaresort.com',
                'amenities' => json_encode(['Wi-Fi', 'Спа', 'Бассейн', 'Аквапарк', 'Рестораны', 'Бар', 'Теннис']),
                'is_active' => true,
            ],
            [
                'name' => 'Гостиница Заря',
                'description' => 'Уютный отель с домашней атмосферой. Отличный вариант для бюджетного размещения в центре города.',
                'address' => 'ул. Ленина, 23',
                'city' => 'Владивосток',
                'country' => 'Россия',
                'star_rating' => 3,
                'contact_phone' => '+7 (423) 245-67-89',
                'email' => 'zarya@hotel.ru',
                'image_url' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/456789012.jpg',
                'room_count' => 85,
                'check_in_time' => '13:00',
                'check_out_time' => '12:00',
                'website' => null,
                'amenities' => json_encode(['Wi-Fi', 'Парковка', 'Кафе']),
                'is_active' => true,
            ],
            [
                'name' => 'Алтай Резорт',
                'description' => 'Эко-отель в горах Алтая. Идеальное место для любителей природы и активного отдыха.',
                'address' => 'Чемальский р-н, с. Чемал',
                'city' => 'Горно-Алтайск',
                'country' => 'Россия',
                'star_rating' => 4,
                'contact_phone' => '+7 (3884) 22-33-44',
                'email' => 'info@altayresort.ru',
                'image_url' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/567890123.jpg',
                'room_count' => 65,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'website' => 'https://www.altayresort.ru',
                'amenities' => json_encode(['Wi-Fi', 'Сауна', 'Ресторан', 'Трансфер', 'Экскурсии']),
                'is_active' => true,
            ],
            [
                'name' => 'Байкал Плаза',
                'description' => 'Отель с видом на озеро Байкал. Номера с панорамными окнами, рыболовные туры, прогулки на катере.',
                'address' => 'ул. Береговая, 10',
                'city' => 'Иркутск',
                'country' => 'Россия',
                'star_rating' => 4,
                'contact_phone' => '+7 (3952) 45-56-67',
                'email' => 'hotel@baikalplaza.ru',
                'image_url' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/678901234.jpg',
                'room_count' => 95,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'website' => 'https://www.baikalplaza.ru',
                'amenities' => json_encode(['Wi-Fi', 'Ресторан', 'Бар', 'Сауна', 'Рыбалка']),
                'is_active' => true,
            ],
            [
                'name' => 'Hyatt Regency Екатеринбург',
                'description' => 'Пятизвездочный отель в деловом центре Екатеринбурга. Современные номера, фитнес-центр, ресторан паназиатской кухни.',
                'address' => 'ул. Бориса Ельцина, 8',
                'city' => 'Екатеринбург',
                'country' => 'Россия',
                'star_rating' => 5,
                'contact_phone' => '+7 (343) 253-12-34',
                'email' => 'ekaterinburg@hyatt.com',
                'image_url' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/789012345.jpg',
                'room_count' => 290,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'website' => 'https://www.hyatt.com',
                'amenities' => json_encode(['Wi-Fi', 'Бассейн', 'Спа', 'Фитнес', 'Ресторан', 'Бизнес-центр']),
                'is_active' => true,
            ],
            [
                'name' => 'Маринс Парк Отель Новосибирск',
                'description' => 'Комфортабельный отель в центре Новосибирска. Отличная транспортная доступность, номера различных категорий.',
                'address' => 'ул. Вокзальная магистраль, 1',
                'city' => 'Новосибирск',
                'country' => 'Россия',
                'star_rating' => 4,
                'contact_phone' => '+7 (383) 310-20-30',
                'email' => 'info@marins-nsk.ru',
                'image_url' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/890123456.jpg',
                'room_count' => 200,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'website' => 'https://www.marins.ru',
                'amenities' => json_encode(['Wi-Fi', 'Ресторан', 'Бар', 'Парковка', 'Конференц-зал']),
                'is_active' => true,
            ],
            [
                'name' => 'Отель Вознесенский Нижний Новгород',
                'description' => 'Отель с видом на Волгу. Расположен в историческом центре города, рядом с кремлём.',
                'address' => 'ул. Большая Покровская, 2',
                'city' => 'Нижний Новгород',
                'country' => 'Россия',
                'star_rating' => 4,
                'contact_phone' => '+7 (831) 422-33-44',
                'email' => 'info@voznesensky.ru',
                'image_url' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/901234567.jpg',
                'room_count' => 110,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'website' => 'https://www.voznesensky.ru',
                'amenities' => json_encode(['Wi-Fi', 'Ресторан', 'Бар', 'Спа', 'Парковка']),
                'is_active' => true,
            ],
            [
                'name' => 'Кемпински Гранд Отель Геленджик',
                'description' => 'Роскошный курортный отель на берегу Геленджикской бухты. Собственный пляж, аквапарк, рестораны.',
                'address' => 'ул. Лермонтовский проспект, 2',
                'city' => 'Геленджик',
                'country' => 'Россия',
                'star_rating' => 5,
                'contact_phone' => '+7 (86141) 9-00-00',
                'email' => 'info@kempinski-gelendzhik.ru',
                'image_url' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/012345678.jpg',
                'room_count' => 350,
                'check_in_time' => '15:00',
                'check_out_time' => '12:00',
                'website' => 'https://www.kempinski.com',
                'amenities' => json_encode(['Wi-Fi', 'Пляж', 'Аквапарк', 'Спа', 'Рестораны', 'Бар', 'Детский клуб']),
                'is_active' => true,
            ],
        ];

        $hotels = [];
        foreach ($hotelsData as $data) {
            $hotels[] = Hotel::create($data);
        }
        $this->command->info('✅ Создано 12 отелей');

        // ========== 2. СОЗДАНИЕ ТУРОВ (12 штук) ==========
        $toursData = [
            ['hotel_id' => $hotels[0]->hotel_id, 'title' => 'Новогодняя сказка в Москве', 'description' => 'Встретьте Новый год в столице! Тур включает проживание в Ритц-Карлтон, экскурсию по праздничной Москве, новогодний ужин в ресторане, посещение катка на Красной площади.', 'destination_country' => 'Россия', 'destination_city' => 'Москва', 'start_date' => '2026-12-28', 'end_date' => '2027-01-04', 'price' => 185000, 'available_spots' => 25, 'image_url' => 'https://via.placeholder.com/800x600', 'is_active' => true],
            ['hotel_id' => $hotels[1]->hotel_id, 'title' => 'Белые ночи Петербурга', 'description' => 'Путешествие в Санкт-Петербург во время белых ночей. Экскурсии по Эрмитажу, Петергофу, разводные мосты, концерт классической музыки.', 'destination_country' => 'Россия', 'destination_city' => 'Санкт-Петербург', 'start_date' => '2026-06-10', 'end_date' => '2026-06-17', 'price' => 89000, 'available_spots' => 35, 'image_url' => 'https://via.placeholder.com/800x600', 'is_active' => true],
            ['hotel_id' => $hotels[2]->hotel_id, 'title' => 'Золотой пляж Сочи', 'description' => 'Отдых на Черном море в Grand Hotel Сочи. Пляжный отдых, экскурсии в Олимпийский парк, посещение дельфинария, дендрария.', 'destination_country' => 'Россия', 'destination_city' => 'Сочи', 'start_date' => '2026-07-15', 'end_date' => '2026-07-22', 'price' => 125000, 'available_spots' => 45, 'image_url' => 'https://via.placeholder.com/800x600', 'is_active' => true],
            ['hotel_id' => $hotels[3]->hotel_id, 'title' => 'Сокровища Казани', 'description' => 'Экскурсионный тур по Казани. Посещение Казанского Кремля, мечети Кул-Шариф, острова-града Свияжск, дегустация татарской кухни.', 'destination_country' => 'Россия', 'destination_city' => 'Казань', 'start_date' => '2026-08-05', 'end_date' => '2026-08-11', 'price' => 68000, 'available_spots' => 30, 'image_url' => 'https://via.placeholder.com/800x600', 'is_active' => true],
            ['hotel_id' => $hotels[4]->hotel_id, 'title' => 'Крымское солнце', 'description' => 'Отдых в Крыму с проживанием в Mriya Resort. Экскурсии в Ливадийский дворец, Ласточкино гнездо, дегустация вин в Массандре.', 'destination_country' => 'Россия', 'destination_city' => 'Ялта', 'start_date' => '2026-08-20', 'end_date' => '2026-08-30', 'price' => 158000, 'available_spots' => 20, 'image_url' => 'https://via.placeholder.com/800x600', 'is_active' => true],
            ['hotel_id' => $hotels[5]->hotel_id, 'title' => 'Край света. Владивосток', 'description' => 'Путешествие на край России. Экскурсия на остров Русский, мыс Тобизина, океанариум, дегустация морепродуктов.', 'destination_country' => 'Россия', 'destination_city' => 'Владивосток', 'start_date' => '2026-09-10', 'end_date' => '2026-09-18', 'price' => 72000, 'available_spots' => 25, 'image_url' => 'https://via.placeholder.com/800x600', 'is_active' => true],
            ['hotel_id' => $hotels[6]->hotel_id, 'title' => 'Алтай. Сила природы', 'description' => 'Активный тур на Алтай. Пешие походы в горы, сплав по реке Катунь, посещение Телецкого озера, шаманские обряды.', 'destination_country' => 'Россия', 'destination_city' => 'Горно-Алтайск', 'start_date' => '2026-09-05', 'end_date' => '2026-09-14', 'price' => 95000, 'available_spots' => 18, 'image_url' => 'https://via.placeholder.com/800x600', 'is_active' => true],
            ['hotel_id' => $hotels[7]->hotel_id, 'title' => 'Загадочный Байкал', 'description' => 'Путешествие к озеру Байкал. Обзорная экскурсия, прогулка по льду (зимой) или катерная прогулка (летом), посещение Иркутска.', 'destination_country' => 'Россия', 'destination_city' => 'Иркутск', 'start_date' => '2027-02-15', 'end_date' => '2027-02-22', 'price' => 82000, 'available_spots' => 28, 'image_url' => 'https://via.placeholder.com/800x600', 'is_active' => true],
            ['hotel_id' => $hotels[8]->hotel_id, 'title' => 'Екатеринбург. Место силы', 'description' => 'Экскурсионный тур по Екатеринбургу. Посещение Храма-на-Крови, Ганиной Ямы, Верхотурья, обзорная экскурсия по городу.', 'destination_country' => 'Россия', 'destination_city' => 'Екатеринбург', 'start_date' => '2026-10-10', 'end_date' => '2026-10-17', 'price' => 65000, 'available_spots' => 30, 'image_url' => 'https://via.placeholder.com/800x600', 'is_active' => true],
            ['hotel_id' => $hotels[9]->hotel_id, 'title' => 'Новосибирск сибирский', 'description' => 'Путешествие в столицу Сибири. Посещение Новосибирского зоопарка, Театра оперы и балета, Академгородка.', 'destination_country' => 'Россия', 'destination_city' => 'Новосибирск', 'start_date' => '2026-11-05', 'end_date' => '2026-11-12', 'price' => 58000, 'available_spots' => 35, 'image_url' => 'https://via.placeholder.com/800x600', 'is_active' => true],
            ['hotel_id' => $hotels[10]->hotel_id, 'title' => 'Нижний Новгород. Столица Поволжья', 'description' => 'Тур в Нижний Новгород. Прогулка по Нижегородскому кремлю, канатная дорога через Волгу, посещение музея-заповедника Болдино.', 'destination_country' => 'Россия', 'destination_city' => 'Нижний Новгород', 'start_date' => '2027-05-20', 'end_date' => '2027-05-27', 'price' => 62000, 'available_spots' => 25, 'image_url' => 'https://via.placeholder.com/800x600', 'is_active' => true],
            ['hotel_id' => $hotels[11]->hotel_id, 'title' => 'Солнечный Геленджик', 'description' => 'Отдых на побережье Геленджика. Пляжный отдых, экскурсии на Сафари-парк, в Старый парк Кабардинка, водные развлечения.', 'destination_country' => 'Россия', 'destination_city' => 'Геленджик', 'start_date' => '2027-07-01', 'end_date' => '2027-07-10', 'price' => 135000, 'available_spots' => 40, 'image_url' => 'https://via.placeholder.com/800x600', 'is_active' => true],
        ];

        $tours = [];
        foreach ($toursData as $data) {
            $tours[] = Tour::create($data);
        }
        $this->command->info('✅ Создано 12 туров');

        // ========== 3. СОЗДАНИЕ ПОЛЬЗОВАТЕЛЕЙ ==========
        $usersData = [
            ['first_name' => 'Админ', 'last_name' => 'Админов', 'email' => 'admin@admin.com', 'phone' => '+7 (999) 111-11-11', 'password' => Hash::make('admin123'), 'role' => 'admin', 'registration_date' => now(), 'is_blocked' => false],
            ['first_name' => 'Александр', 'last_name' => 'Петров', 'email' => 'alex@mail.ru', 'phone' => '+7 (901) 111-22-33', 'password' => Hash::make('password123'), 'role' => 'user', 'registration_date' => now(), 'is_blocked' => false],
            ['first_name' => 'Елена', 'last_name' => 'Смирнова', 'email' => 'elena@mail.ru', 'phone' => '+7 (902) 222-33-44', 'password' => Hash::make('password123'), 'role' => 'user', 'registration_date' => now(), 'is_blocked' => false],
            ['first_name' => 'Дмитрий', 'last_name' => 'Иванов', 'email' => 'dima@mail.ru', 'phone' => '+7 (903) 333-44-55', 'password' => Hash::make('password123'), 'role' => 'user', 'registration_date' => now(), 'is_blocked' => false],
            ['first_name' => 'Мария', 'last_name' => 'Кузнецова', 'email' => 'maria@mail.ru', 'phone' => '+7 (904) 444-55-66', 'password' => Hash::make('password123'), 'role' => 'user', 'registration_date' => now(), 'is_blocked' => false],
            ['first_name' => 'Андрей', 'last_name' => 'Соколов', 'email' => 'andrey@mail.ru', 'phone' => '+7 (905) 555-66-77', 'password' => Hash::make('password123'), 'role' => 'user', 'registration_date' => now(), 'is_blocked' => false],
            ['first_name' => 'Ольга', 'last_name' => 'Михайлова', 'email' => 'olga@mail.ru', 'phone' => '+7 (906) 666-77-88', 'password' => Hash::make('password123'), 'role' => 'user', 'registration_date' => now(), 'is_blocked' => false],
            ['first_name' => 'Сергей', 'last_name' => 'Федоров', 'email' => 'sergey@mail.ru', 'phone' => '+7 (907) 777-88-99', 'password' => Hash::make('password123'), 'role' => 'user', 'registration_date' => now(), 'is_blocked' => false],
            ['first_name' => 'Наталья', 'last_name' => 'Морозова', 'email' => 'natalia@mail.ru', 'phone' => '+7 (908) 888-99-00', 'password' => Hash::make('password123'), 'role' => 'user', 'registration_date' => now(), 'is_blocked' => false],
        ];

        $users = [];
        foreach ($usersData as $data) {
            $users[] = Client::create($data);
        }
        $this->command->info('✅ Создано 9 пользователей');
        $this->command->info('👤 Админ: admin@admin.com / admin123');
        $this->command->info('👤 Пользователь: alex@mail.ru / password123');

        // ========== 4. СОЗДАНИЕ БРОНИРОВАНИЙ ==========
        $bookingsData = [
            ['client_id' => $users[1]->client_id, 'tour_id' => $tours[0]->tour_id, 'people' => 2, 'status' => 'confirmed', 'price' => 185000 * 2],
            ['client_id' => $users[2]->client_id, 'tour_id' => $tours[1]->tour_id, 'people' => 1, 'status' => 'confirmed', 'price' => 89000],
            ['client_id' => $users[3]->client_id, 'tour_id' => $tours[2]->tour_id, 'people' => 4, 'status' => 'completed', 'price' => 125000 * 4],
            ['client_id' => $users[4]->client_id, 'tour_id' => $tours[3]->tour_id, 'people' => 2, 'status' => 'confirmed', 'price' => 68000 * 2],
            ['client_id' => $users[5]->client_id, 'tour_id' => $tours[4]->tour_id, 'people' => 2, 'status' => 'completed', 'price' => 158000 * 2],
            ['client_id' => $users[6]->client_id, 'tour_id' => $tours[5]->tour_id, 'people' => 1, 'status' => 'cancelled', 'price' => 72000],
            ['client_id' => $users[7]->client_id, 'tour_id' => $tours[6]->tour_id, 'people' => 3, 'status' => 'confirmed', 'price' => 95000 * 3],
            ['client_id' => $users[8]->client_id, 'tour_id' => $tours[7]->tour_id, 'people' => 2, 'status' => 'confirmed', 'price' => 82000 * 2],
            ['client_id' => $users[1]->client_id, 'tour_id' => $tours[2]->tour_id, 'people' => 2, 'status' => 'confirmed', 'price' => 125000 * 2],
            ['client_id' => $users[2]->client_id, 'tour_id' => $tours[4]->tour_id, 'people' => 1, 'status' => 'completed', 'price' => 158000],
            ['client_id' => $users[3]->client_id, 'tour_id' => $tours[6]->tour_id, 'people' => 2, 'status' => 'confirmed', 'price' => 95000 * 2],
            ['client_id' => $users[4]->client_id, 'tour_id' => $tours[0]->tour_id, 'people' => 3, 'status' => 'pending', 'price' => 185000 * 3],
        ];

        foreach ($bookingsData as $data) {
            $booking = Booking::create([
                'client_id' => $data['client_id'],
                'tour_id' => $data['tour_id'],
                'booking_date' => now()->subDays(rand(1, 60)),
                'number_of_people' => $data['people'],
                'status' => $data['status'],
                'total_price' => $data['price'],
            ]);
            
            if ($data['status'] === 'confirmed' || $data['status'] === 'completed') {
                $tour = Tour::find($data['tour_id']);
                $tour->decrement('available_spots', $data['people']);
            }
        }
        $this->command->info('✅ Создано 12 бронирований');

        // ========== 5. СОЗДАНИЕ ОТЗЫВОВ ==========
        $reviewsData = [
            ['client_id' => $users[1]->client_id, 'tour_id' => $tours[0]->tour_id, 'rating' => 5, 'comment' => 'Незабываемая поездка! Москва прекрасна в любое время года, а Новый год в столице - это сказка!', 'moderation_status' => 'approved'],
            ['client_id' => $users[2]->client_id, 'tour_id' => $tours[1]->tour_id, 'rating' => 5, 'comment' => 'Белые ночи - это магия! Петербург в это время особенно красив. Эрмитаж, разводные мосты - всё супер!', 'moderation_status' => 'approved'],
            ['client_id' => $users[3]->client_id, 'tour_id' => $tours[2]->tour_id, 'rating' => 4, 'comment' => 'Отличный отдых на море. Очень понравился отель, питание вкусное. В следующий раз возьмём на неделю больше.', 'moderation_status' => 'approved'],
            ['client_id' => $users[4]->client_id, 'tour_id' => $tours[3]->tour_id, 'rating' => 5, 'comment' => 'Казань - прекрасный город! Кремль впечатляет, экскурсии очень интересные. Обязательно приедем ещё раз!', 'moderation_status' => 'approved'],
            ['client_id' => $users[5]->client_id, 'tour_id' => $tours[4]->tour_id, 'rating' => 5, 'comment' => 'Крым прекрасен! Mriya Resort - это рай на земле. Вина Массандры - отдельная любовь.', 'moderation_status' => 'approved'],
            ['client_id' => $users[6]->client_id, 'tour_id' => $tours[5]->tour_id, 'rating' => 4, 'comment' => 'Владивосток суров, но красив. Океан впечатляет, виды потрясающие.', 'moderation_status' => 'pending'],
            ['client_id' => $users[7]->client_id, 'tour_id' => $tours[6]->tour_id, 'rating' => 5, 'comment' => 'Алтай - место силы! Невероятная природа, чистый воздух, горы. Спасибо организаторам за отличный тур!', 'moderation_status' => 'approved'],
            ['client_id' => $users[8]->client_id, 'tour_id' => $tours[7]->tour_id, 'rating' => 5, 'comment' => 'Байкал - это что-то невероятное! Вода чистейшая, виды захватывающие. Очень понравилось!', 'moderation_status' => 'approved'],
        ];

        foreach ($reviewsData as $data) {
            Review::create([
                'client_id' => $data['client_id'],
                'tour_id' => $data['tour_id'],
                'rating' => $data['rating'],
                'comment' => $data['comment'],
                'moderation_status' => $data['moderation_status'],
                'created_at' => now()->subDays(rand(1, 30)),
                'has_profanity' => false,
            ]);
        }
        $this->command->info('✅ Создано 8 отзывов');

        $this->command->info('');
        $this->command->info('🎉 ГОТОВО!');
        $this->command->info('=================================');
        $this->command->info('🏨 Отелей: 12');
        $this->command->info('✈️ Туров: 12 (даты 2026-2027)');
        $this->command->info('👤 Пользователей: 9');
        $this->command->info('📋 Бронирований: 12');
        $this->command->info('⭐ Отзывов: 8');
        $this->command->info('=================================');
        $this->command->info('👤 АДМИН: admin@admin.com');
        $this->command->info('🔑 Пароль: admin123');
        $this->command->info('=================================');
        $this->command->info('👤 ПОЛЬЗОВАТЕЛЬ: alex@mail.ru');
        $this->command->info('🔑 Пароль: password123');
        $this->command->info('=================================');
    }
}
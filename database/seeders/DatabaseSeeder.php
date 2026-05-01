<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Aircraft;
use App\Models\Airport;
use App\Models\Booking;
use App\Models\City;
use App\Models\Faq;
use App\Models\FlightRoute;
use App\Models\FlightSchedule;
use App\Models\Notification;
use App\Models\Promo;
use App\Models\TicketClass;
use App\Models\TicketPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = collect([
            ['name' => 'customer', 'display_name' => 'Customer', 'description' => 'Pengguna publik untuk booking tiket online.'],
            ['name' => 'admin', 'display_name' => 'Admin', 'description' => 'Operator data master, booking offline, dan konfirmasi pembayaran.'],
            ['name' => 'manager', 'display_name' => 'Manager', 'description' => 'Pemantau laporan dan approval promo.'],
            ['name' => 'ceo', 'display_name' => 'CEO', 'description' => 'Akses strategis read-only dan laporan eksekutif.'],
        ])->mapWithKeys(fn (array $role) => [
            $role['name'] => Role::updateOrCreate(['name' => $role['name']], $role),
        ]);

        $users = [
            ['name' => 'Customer Demo', 'email' => 'customer@example.com', 'role' => 'customer'],
            ['name' => 'Admin Demo', 'email' => 'admin@example.com', 'role' => 'admin'],
            ['name' => 'Manager Demo', 'email' => 'manager@example.com', 'role' => 'manager'],
            ['name' => 'CEO Demo', 'email' => 'ceo@example.com', 'role' => 'ceo'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'role_id' => $roles[$user['role']]->id,
                    'name' => $user['name'],
                    'phone' => '0800000000',
                    'address' => 'Jakarta, Indonesia',
                    'password' => Hash::make('password'),
                ],
            );
        }

        $cities = collect([
            ['name' => 'Jakarta', 'province' => 'DKI Jakarta'],
            ['name' => 'Surabaya', 'province' => 'Jawa Timur'],
            ['name' => 'Denpasar', 'province' => 'Bali'],
            ['name' => 'Medan', 'province' => 'Sumatera Utara'],
            ['name' => 'Makassar', 'province' => 'Sulawesi Selatan'],
            ['name' => 'Singapore', 'province' => null, 'country' => 'Singapore'],
            ['name' => 'Kuala Lumpur', 'province' => null, 'country' => 'Malaysia'],
            ['name' => 'Bangkok', 'province' => null, 'country' => 'Thailand'],
            ['name' => 'Tokyo', 'province' => null, 'country' => 'Japan'],
            ['name' => 'Seoul', 'province' => null, 'country' => 'South Korea'],
        ])->mapWithKeys(fn ($city) => [
            $city['name'] => City::updateOrCreate(
                ['name' => $city['name']],
                ['province' => $city['province'] ?? null, 'country' => $city['country'] ?? 'Indonesia'],
            ),
        ]);

        $airports = collect([
            ['code' => 'CGK', 'name' => 'Soekarno-Hatta International Airport', 'city' => 'Jakarta', 'country' => 'Indonesia'],
            ['code' => 'SUB', 'name' => 'Juanda International Airport', 'city' => 'Surabaya', 'country' => 'Indonesia'],
            ['code' => 'DPS', 'name' => 'I Gusti Ngurah Rai International Airport', 'city' => 'Denpasar', 'country' => 'Indonesia'],
            ['code' => 'KNO', 'name' => 'Kualanamu International Airport', 'city' => 'Medan', 'country' => 'Indonesia'],
            ['code' => 'UPG', 'name' => 'Sultan Hasanuddin International Airport', 'city' => 'Makassar', 'country' => 'Indonesia'],
            ['code' => 'SIN', 'name' => 'Singapore Changi Airport', 'city' => 'Singapore', 'country' => 'Singapore'],
            ['code' => 'KUL', 'name' => 'Kuala Lumpur International Airport', 'city' => 'Kuala Lumpur', 'country' => 'Malaysia'],
            ['code' => 'BKK', 'name' => 'Suvarnabhumi Airport', 'city' => 'Bangkok', 'country' => 'Thailand'],
            ['code' => 'HND', 'name' => 'Tokyo Haneda Airport', 'city' => 'Tokyo', 'country' => 'Japan'],
            ['code' => 'ICN', 'name' => 'Incheon International Airport', 'city' => 'Seoul', 'country' => 'South Korea'],
        ])->mapWithKeys(fn ($airport) => [
            $airport['code'] => Airport::updateOrCreate(
                ['code' => $airport['code']],
                ['city_id' => $cities[$airport['city']]->id, 'name' => $airport['name'], 'address' => $airport['city'].', '.$airport['country']],
            ),
        ]);

        $aircrafts = collect([
            ['registration_code' => 'PK-AIR01', 'model' => 'Airbus A320 Neo', 'seat_rows' => 8, 'seat_columns' => 'A,B,C,D', 'capacity' => 32],
            ['registration_code' => 'PK-AIR02', 'model' => 'Boeing 737-800', 'seat_rows' => 10, 'seat_columns' => 'A,B,C,D,E,F', 'capacity' => 60],
        ])->mapWithKeys(function ($aircraft) {
            $model = Aircraft::updateOrCreate(['registration_code' => $aircraft['registration_code']], [...$aircraft, 'status' => 'active']);
            $columns = explode(',', $model->seat_columns);
            if ($model->seats()->count() === 0) {
                for ($row = 1; $row <= $model->seat_rows; $row++) {
                    foreach ($columns as $column) {
                        $model->seats()->create([
                            'code' => $column.$row,
                            'row_number' => $row,
                            'column_letter' => $column,
                            'seat_type' => $row <= 2 ? 'premium' : 'standard',
                            'is_active' => true,
                        ]);
                    }
                }
            }

            return [$model->registration_code => $model];
        });

        $routes = collect([
            ['code' => 'CGK-SUB', 'origin' => 'CGK', 'destination' => 'SUB', 'distance_km' => 691, 'duration_minutes' => 95],
            ['code' => 'CGK-DPS', 'origin' => 'CGK', 'destination' => 'DPS', 'distance_km' => 982, 'duration_minutes' => 115],
            ['code' => 'SUB-DPS', 'origin' => 'SUB', 'destination' => 'DPS', 'distance_km' => 303, 'duration_minutes' => 55],
            ['code' => 'CGK-KNO', 'origin' => 'CGK', 'destination' => 'KNO', 'distance_km' => 1390, 'duration_minutes' => 140],
            ['code' => 'CGK-UPG', 'origin' => 'CGK', 'destination' => 'UPG', 'distance_km' => 1432, 'duration_minutes' => 150],
            ['code' => 'CGK-SIN', 'origin' => 'CGK', 'destination' => 'SIN', 'distance_km' => 882, 'duration_minutes' => 105],
            ['code' => 'SIN-CGK', 'origin' => 'SIN', 'destination' => 'CGK', 'distance_km' => 882, 'duration_minutes' => 105],
            ['code' => 'CGK-KUL', 'origin' => 'CGK', 'destination' => 'KUL', 'distance_km' => 1129, 'duration_minutes' => 125],
            ['code' => 'KUL-CGK', 'origin' => 'KUL', 'destination' => 'CGK', 'distance_km' => 1129, 'duration_minutes' => 125],
            ['code' => 'DPS-BKK', 'origin' => 'DPS', 'destination' => 'BKK', 'distance_km' => 2970, 'duration_minutes' => 255],
            ['code' => 'BKK-DPS', 'origin' => 'BKK', 'destination' => 'DPS', 'distance_km' => 2970, 'duration_minutes' => 255],
            ['code' => 'CGK-HND', 'origin' => 'CGK', 'destination' => 'HND', 'distance_km' => 5790, 'duration_minutes' => 430],
            ['code' => 'HND-CGK', 'origin' => 'HND', 'destination' => 'CGK', 'distance_km' => 5790, 'duration_minutes' => 430],
            ['code' => 'CGK-ICN', 'origin' => 'CGK', 'destination' => 'ICN', 'distance_km' => 5276, 'duration_minutes' => 420],
            ['code' => 'ICN-CGK', 'origin' => 'ICN', 'destination' => 'CGK', 'distance_km' => 5276, 'duration_minutes' => 420],
        ])->mapWithKeys(fn ($route) => [
            $route['code'] => FlightRoute::updateOrCreate(
                ['code' => $route['code']],
                [
                    'origin_airport_id' => $airports[$route['origin']]->id,
                    'destination_airport_id' => $airports[$route['destination']]->id,
                    'distance_km' => $route['distance_km'],
                    'duration_minutes' => $route['duration_minutes'],
                    'is_active' => true,
                ],
            ),
        ]);

        $classes = collect([
            ['name' => 'Economy', 'code' => 'ECO', 'description' => 'Kabin ekonomis dengan fasilitas standar.'],
            ['name' => 'Business', 'code' => 'BUS', 'description' => 'Kabin nyaman untuk perjalanan bisnis.'],
            ['name' => 'First Class', 'code' => 'FST', 'description' => 'Layanan premium dengan prioritas tertinggi.'],
        ])->mapWithKeys(fn ($class) => [$class['code'] => TicketClass::updateOrCreate(['code' => $class['code']], $class)]);

        $scheduleRows = [
            ['flight_number' => 'AI-101', 'aircraft' => 'PK-AIR01', 'route' => 'CGK-SUB', 'departure' => now()->addDays(1)->setTime(8, 0)],
            ['flight_number' => 'AI-202', 'aircraft' => 'PK-AIR02', 'route' => 'CGK-DPS', 'departure' => now()->addDays(2)->setTime(10, 30)],
            ['flight_number' => 'AI-303', 'aircraft' => 'PK-AIR01', 'route' => 'SUB-DPS', 'departure' => now()->addDays(3)->setTime(14, 15)],
            ['flight_number' => 'AI-404', 'aircraft' => 'PK-AIR02', 'route' => 'CGK-KNO', 'departure' => now()->addDays(4)->setTime(7, 45)],
            ['flight_number' => 'AI-505', 'aircraft' => 'PK-AIR02', 'route' => 'CGK-UPG', 'departure' => now()->addDays(5)->setTime(16, 10)],
            ['flight_number' => 'AI-601', 'aircraft' => 'PK-AIR02', 'route' => 'CGK-SIN', 'departure' => now()->addDays(2)->setTime(9, 20)],
            ['flight_number' => 'AI-602', 'aircraft' => 'PK-AIR02', 'route' => 'SIN-CGK', 'departure' => now()->addDays(6)->setTime(18, 10)],
            ['flight_number' => 'AI-701', 'aircraft' => 'PK-AIR02', 'route' => 'CGK-KUL', 'departure' => now()->addDays(3)->setTime(11, 0)],
            ['flight_number' => 'AI-702', 'aircraft' => 'PK-AIR02', 'route' => 'KUL-CGK', 'departure' => now()->addDays(7)->setTime(17, 35)],
            ['flight_number' => 'AI-801', 'aircraft' => 'PK-AIR02', 'route' => 'DPS-BKK', 'departure' => now()->addDays(4)->setTime(13, 45)],
            ['flight_number' => 'AI-802', 'aircraft' => 'PK-AIR02', 'route' => 'BKK-DPS', 'departure' => now()->addDays(8)->setTime(20, 25)],
            ['flight_number' => 'AI-901', 'aircraft' => 'PK-AIR02', 'route' => 'CGK-HND', 'departure' => now()->addDays(5)->setTime(23, 30)],
            ['flight_number' => 'AI-902', 'aircraft' => 'PK-AIR02', 'route' => 'HND-CGK', 'departure' => now()->addDays(10)->setTime(10, 0)],
            ['flight_number' => 'AI-911', 'aircraft' => 'PK-AIR02', 'route' => 'CGK-ICN', 'departure' => now()->addDays(6)->setTime(22, 10)],
            ['flight_number' => 'AI-912', 'aircraft' => 'PK-AIR02', 'route' => 'ICN-CGK', 'departure' => now()->addDays(11)->setTime(9, 40)],
        ];

        $schedules = collect($scheduleRows)->mapWithKeys(function ($row) use ($aircrafts, $routes) {
            $route = $routes[$row['route']];
            $schedule = FlightSchedule::updateOrCreate(
                ['flight_number' => $row['flight_number']],
                [
                    'aircraft_id' => $aircrafts[$row['aircraft']]->id,
                    'route_id' => $route->id,
                    'departure_time' => $row['departure'],
                    'arrival_time' => $row['departure']->copy()->addMinutes($route->duration_minutes),
                    'status' => 'scheduled',
                ],
            );

            return [$row['flight_number'] => $schedule];
        });

        foreach ($schedules as $schedule) {
            foreach ($classes as $class) {
                $multiplier = match ($class->code) {
                    'BUS' => 2.2,
                    'FST' => 3.8,
                    default => 1,
                };

                TicketPrice::updateOrCreate(
                    ['flight_schedule_id' => $schedule->id, 'ticket_class_id' => $class->id],
                    ['price' => (450000 + ($schedule->route->distance_km * 700)) * $multiplier, 'quota' => $schedule->aircraft->capacity],
                );
            }
        }

        $admin = User::where('email', 'admin@example.com')->first();
        $manager = User::where('email', 'manager@example.com')->first();

        Promo::updateOrCreate(
            ['code' => 'AIRHEMAT20'],
            [
                'created_by' => $admin->id,
                'approved_by' => $manager->id,
                'title' => 'Hemat Terbang 20%',
                'description' => 'Diskon simulasi untuk penerbangan domestik pilihan.',
                'discount_percent' => 20,
                'start_date' => now()->subDay()->toDateString(),
                'end_date' => now()->addMonth()->toDateString(),
                'status' => 'approved',
            ],
        );

        Faq::updateOrCreate(['question' => 'Bagaimana cara memilih kursi?'], ['answer' => 'Pilih jadwal penerbangan, lalu klik kursi yang masih tersedia pada seat map.', 'is_active' => true]);
        Faq::updateOrCreate(['question' => 'Kapan booking pending expired?'], ['answer' => 'Booking online pending otomatis dianggap expired secara logika setelah 6 jam.', 'is_active' => true]);

        $customer = User::where('email', 'customer@example.com')->first();
        Notification::updateOrCreate(
            ['user_id' => $customer->id, 'title' => 'Selamat datang'],
            ['message' => 'Akun demo customer siap digunakan untuk mencoba booking tiket.'],
        );

        if (Booking::where('booking_code', 'AIRDEMO001')->doesntExist()) {
            $schedule = $schedules['AI-101'];
            $class = $classes['ECO'];
            $seat = $schedule->aircraft->seats()->first();
            $booking = Booking::create([
                'user_id' => $customer->id,
                'flight_schedule_id' => $schedule->id,
                'ticket_class_id' => $class->id,
                'created_by' => $admin->id,
                'booking_code' => 'AIRDEMO001',
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone,
                'passenger_count' => 1,
                'subtotal' => 950000,
                'discount' => 0,
                'total_amount' => 950000,
                'status' => 'confirmed',
                'source' => 'offline',
                'confirmed_at' => now(),
            ]);
            $booking->passengers()->create(['name' => $customer->name, 'identity_number' => '3173000000000001', 'gender' => 'male']);
            $booking->bookingSeats()->create(['seat_id' => $seat->id, 'flight_schedule_id' => $schedule->id]);
            $booking->payment()->create(['method' => 'cash', 'amount' => 950000, 'status' => 'confirmed', 'paid_at' => now(), 'confirmed_by' => $admin->id, 'confirmed_at' => now()]);
        }
    }
}

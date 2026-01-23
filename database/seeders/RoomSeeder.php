<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [];

        // Floors 1-9: 10 rooms each
        for ($floor = 1; $floor <= 9; $floor++) {
            for ($num = 1; $num <= 10; $num++) {
                $rooms[] = [
                    'floor' => $floor,
                    'number' => $floor * 100 + $num,
                    'is_booked' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Floor 10: 7 rooms
        for ($num = 1; $num <= 7; $num++) {
            $rooms[] = [
                'floor' => 10,
                'number' => 1000 + $num,
                'is_booked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('rooms')->insert($rooms);
    }
}

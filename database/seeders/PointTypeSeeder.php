<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PointTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('point_types')->upsert(
            [
                ['name' => 'Point of Interest', 'icon' => 'map-pin', 'colour' => '#c2410c'],
                ['name' => 'Meeting Point', 'icon' => 'users', 'colour' => '#eab308'],
                ['name' => 'Camp Spot', 'icon' => 'tent', 'colour' => '#3b7a57'],
                ['name' => 'Parking', 'icon' => 'square-parking', 'colour' => '#6ebdeeff'],
                ['name' => 'Food & Drink', 'icon' => 'soup', 'colour' => '#7b687d'],
                ['name' => 'Cafe', 'icon' => 'coffee', 'colour' => '#A0522D'],
                ['name' => 'Shop', 'icon' => 'store', 'colour' => '#4682B4'],
                ['name' => 'Mountain', 'icon' => 'mountain', 'colour' => '#7B3F00'],
                ['name' => 'Lake', 'icon' => 'waves', 'colour' => '#1E90FF'],
                ['name' => 'Forest', 'icon' => 'trees', 'colour' => '#228B22'],
                ['name' => 'Viewpoint', 'icon' => 'binoculars', 'colour' => '#FFD700'],
                ['name' => 'Other', 'icon' => 'heart', 'colour' => '#acc286'],
            ],
            ['name'], // Unique constraint column
            ['icon', 'colour'] // Columns to update if conflict occurs
        );
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        if(app()->environment('local')){
            DB::table('colors')->truncate();
        }
        $colors = [
            ['name' => 'Red', 'hex_code' => '#FF0000'],
            ['name' => 'Blue', 'hex_code' => '#0000FF'],
            ['name' => 'Green', 'hex_code' => '#008000'],
            ['name' => 'Black', 'hex_code' => '#000000'],
            ['name' => 'White', 'hex_code' => '#FFFFFF'],
            ['name' => 'Yellow', 'hex_code' => '#FFFF00'],
            ['name' => 'Purple', 'hex_code' => '#800080'],
            ['name' => 'Orange', 'hex_code' => '#FFA500'],
            ['name' => 'Pink', 'hex_code' => '#FFC0CB'],
            ['name' => 'Gray', 'hex_code' => '#808080'],
        ];

        foreach ($colors as $color) {
            Color::firstOrCreate(['name' => $color['name']], ['hex_code' => $color['hex_code']]);
        }
    }
}

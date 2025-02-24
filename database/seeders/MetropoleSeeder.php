<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Metropole;
use App\Models\City;

class MetropoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Metropole "Lille"
        $metropole = Metropole::create(['name' => 'Lille']);

        // Create cities for the metropole
        $cities = ['Lille', 'Roubaix', 'Tourcoing', 'Villeneuve d\'Ascq', 'Armentières'];

        foreach ($cities as $cityName) {
            City::create([
                'name' => $cityName,
                'metropole_id' => $metropole->id,
            ]);
        }
    }
}

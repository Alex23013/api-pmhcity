<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Metropole;
use App\Models\City;
use Illuminate\Support\Facades\DB;

class MetropoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(app()->environment('local')){
            DB::table('cities')->truncate();
            DB::table('metropoles')->truncate();
        }
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

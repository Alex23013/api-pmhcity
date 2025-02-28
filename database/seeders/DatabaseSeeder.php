<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        $this->call(RoleSeeder::class);
        $this->call(MetropoleSeeder::class);
        */
        $this->call(CategorySeeder::class);
        $this->call(ReservationStatusSeeder::class);
        $this->call(DataProductSeeder::class);
        $this->call(ColorSeeder::class);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReservationStatus;

class ReservationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $statuses = [
            ['name' => 'created', 'display_name' => 'créé'],
            ['name' => 'accepted', 'display_name' => 'accepté'],
            ['name' => 'declined', 'display_name' => 'annulé'],
            ['name' => 'payed', 'display_name' => 'payé'],
            ['name' => 'in transit', 'display_name' => 'en transit'],
            ['name' => 'delivered', 'display_name' => 'livré'],
        ];

        foreach ($statuses as $status) {
            ReservationStatus::firstOrCreate(['name' => $status['name']], $status);
        }
    }
}

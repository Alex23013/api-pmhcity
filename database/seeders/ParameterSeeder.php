<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parameter;
use Illuminate\Support\Facades\DB;

class ParameterSeeder extends Seeder
{
    public function run(): void
    {
        if(app()->environment('local')){
            DB::table('parameters')->truncate();
        }
        $parameters = [
            [
                'name' => 'maintenance_mode',
                'display_name' => 'Maintenance Mode',
                'description' => 'Enable or disable maintenance mode for the application.',
                'value' => 'off',
            ],
            [
                'name' => 'default_language',
                'display_name' => 'Default Language',
                'description' => 'The default language for the application interface.',
                'value' => 'en',
            ],
            [
                'name' => 'pmh_relay_delivery_price',
                'display_name' => 'PMH Relay Delivery Price',
                'description' => 'The standard delivery price applied to orders.',
                'value' => '5.00',
            ],
            [
                'name' => 'sale_comission_percentage',
                'display_name' => 'Sale Commission Percentage',
                'description' => 'The percentage of commission taken from each sale.',
                'value' => '2.5',
            ]
        ];

        foreach ($parameters as $param) {
            Parameter::firstOrCreate(['name' => $param['name']], $param);
        }
    } 
}

<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            1 => 'Admin',
            2 => 'Commercant Brand',
            3 => 'Commercant Commercial',
            4 => 'Buyer'
        ];

        foreach ($roles as $id => $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $id],
                [
                    'name' => $role,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

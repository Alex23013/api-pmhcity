<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Admin User",
            'lastname' => "Pro",
            'email' => "admin@yopmail.com",
            'password' => Hash::make("admin123"),
            'role_id' => 1,
            'is_admin' => true
        ]);

        DB::table('users')->insert([
            'name' => "Seller 1 User",
            'lastname' => "Pro",
            'email' => "seller@yopmail.com",
            'password' => Hash::make("seller123"),
            'role_id' => 3
        ]);

        $sellerId = DB::table('users')->where('email', 'seller@yopmail.com')->value('id');

        DB::table('stores')->insert([
            'name' => 'Seller 1 Store',
            'address' => '123 Seller St',
            'zip_code' => '12345',
            'user_id' => $sellerId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => "Buyer User",
            'lastname' => "Pro",
            'email' => "buyer@yopmail.com",
            'password' => Hash::make("buyer123"),
            'role_id' => 4
        ]);
    }
}

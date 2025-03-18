<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Size;
use App\Models\SizeType;

class SizeWithTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch size types by name (assuming they are already seeded)
        $letterType = SizeType::where('name', 'letter')->first();
        $kidsShoesType = SizeType::where('name', 'kids_shoes')->first();
        $adultShoesType = SizeType::where('name', 'adult_shoes')->first();
        $numbersType = SizeType::where('name', 'numbers')->first();
        $uniqueType = SizeType::where('name', 'unique')->first();

        // Insert letter sizes
        $letterSizes = ['XXXS', 'XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL'];
        foreach ($letterSizes as $sizeName) {
            Size::firstOrCreate([
                'name' => $sizeName,
                'size_type_id' => $letterType->id,
            ]);
        }

        // Insert kids shoes sizes (16 to 33)
        for ($i = 16; $i <= 33; $i++) {
            Size::firstOrCreate([
                'name' => (string) $i,
                'size_type_id' => $kidsShoesType->id,
            ]);
        }

        // Insert adult shoes sizes (34 to 44)
        for ($i = 34; $i <= 44; $i++) {
            Size::firstOrCreate([
                'name' => (string) $i,
                'size_type_id' => $adultShoesType->id,
            ]);
        }

        // Insert number sizes (2 to 30, step 2)
        for ($i = 2; $i <= 30; $i += 2) {
            Size::firstOrCreate([
                'name' => (string) $i,
                'size_type_id' => $numbersType->id,
            ]);
        }

        // Insert unique size
        Size::firstOrCreate([
            'name' => 'Taille unique',
            'size_type_id' => $uniqueType->id,
        ]);
    }
}

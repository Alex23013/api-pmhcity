<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SizeType;

class SizeTypeSeeder extends Seeder
{
    public function run(): void
    {
        $sizeTypes = [
            'letter',    // Example: XS, S, M, L, XL
            'adult_shoes',
            'kids_shoes',     
            'numbers',
            'unique',    // One size fits all
        ];

        foreach ($sizeTypes as $type) {
            SizeType::firstOrCreate(['name' => $type]);
        }
    }
}

<?php

namespace Database\Factories;

use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubcategoryFactory extends Factory
{
    protected $model = Subcategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'category_id' => Category::factory(),
        ];
    }
}

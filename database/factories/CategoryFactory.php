<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
        ];
    }


    /**
     * Attach subcategories to the category.
     *
     * @param int $count
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withSubcategories(int $count = 3): Factory
    {
        return $this->has(Subcategory::factory()->count($count));
    }

    // Category with 3 subcategories (default) --> $category = Category::factory()->withSubcategories()->create();
    // Category with 5 subcategories --> $category = Category::factory()->withSubcategories(5)->create();
}

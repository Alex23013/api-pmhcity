<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role_id' => 3]), // seller user
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->optional()->sentence(),
            'price' => $this->faker->randomFloat(2, 5, 500),
            'category_id' => Category::factory(),
            'subcategory_id' => Subcategory::factory(),
            'is_active' => true,
        ];
    }

    /**
     * State: inactive product
     */
    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}

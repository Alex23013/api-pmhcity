<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;


    public function definition(): array
    {
        return [
            'last_status' => 'accepted', // could also be "pending", "cancelled" etc.
            'comment' => $this->faker->optional()->sentence(),
            'size_id' => null, // update if you have a Size model + seeder
            'quantity' => $this->faker->numberBetween(1, 5),
            'color_id' => null, // update if you have a Color model + seeder
            'price' => $this->faker->randomFloat(2, 10, 200),

            // Relations
            'buyer_id' => User::factory()->state(['role_id' => 4]), // assume 4 = buyer
            'seller_id' => User::factory()->state(['role_id' => 3]), // assume 3 = seller
            'product_id' => Product::factory(),
        ];
    }

    public function created(): static
    {
        return $this->state(fn () => ['last_status' => 'created']);
    }

    public function accepted(): static
    {
        return $this->state(fn () => ['last_status' => 'accepted']);
    }

    public function completed(): static
    {
        return $this->state(fn () => ['last_status' => 'completed']);
    }
}

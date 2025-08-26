<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $types = ['earning', 'withdrawal', 'refund', 'adjustment'];

        return [
            'user_id'        => User::factory(), // creates a user if not provided
            'type'           => $this->faker->randomElement($types),
            'amount'         => $this->faker->randomFloat(2, -200, 200), // can be negative
            'reference_type' => null,
            'reference_id'   => null,
            'created_at'     => now(),
            'updated_at'     => now(),
        ];
    }

    /**
     * State for earnings
     */
    public function earning($amount = null): static
    {
        return $this->state(fn () => [
            'type' => 'earning',
            'amount' => $amount ?? $this->faker->randomFloat(2, 10, 500),
        ]);
    }

    /**
     * State for withdrawals
     */
    public function withdrawal($amount = null): static
    {
        return $this->state(fn () => [
            'type' => 'withdrawal',
            'amount' => -abs($amount ?? $this->faker->randomFloat(2, 10, 500)),
        ]);
    }
}

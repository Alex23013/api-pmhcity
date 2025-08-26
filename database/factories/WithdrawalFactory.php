<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Database\Eloquent\Factories\Factory;

class WithdrawalFactory extends Factory
{
    protected $model = Withdrawal::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->seller(), // assumes withdrawals are from sellers
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'status' => 'pending',
            'iban' => $this->faker->iban(),
            'operation_code' => null,
            'method' => 'bank',
            'notes' => $this->faker->sentence(),
            'requested_at' => now(),
            'processed_at' => null,
        ];
    }

    /**
     * State: approved withdrawal
     */
    public function approved(): Factory
    {
        return $this->state(fn () => [
            'status' => 'approved',
            'processed_at' => now(),
            'operation_code' => strtoupper($this->faker->bothify('OP-####')),
        ]);
    }

    /**
     * State: paid withdrawal
     */
    public function paid(): Factory
    {
        return $this->state(fn () => [
            'status' => 'paid',
            'processed_at' => now(),
            'operation_code' => strtoupper($this->faker->bothify('PAY-####')),
        ]);
    }

    /**
     * State: rejected withdrawal
     */
    public function rejected(): Factory
    {
        return $this->state(fn () => [
            'status' => 'rejected',
            'processed_at' => now(),
            'notes' => 'Withdrawal rejected due to verification failure.',
        ]);
    }
}

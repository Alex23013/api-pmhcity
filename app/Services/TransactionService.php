<?php
namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Parameter;
use App\Models\Reservation;

class TransactionService
{
    public function createEarning(Reservation $reservation)
    {
        $seller = User::find($reservation->seller_id);
        $saleComissionPercentage = Parameter::where('name', 'sale_comission_percentage')->first();
        $saleComissionPercentage = $saleComissionPercentage ? ($saleComissionPercentage->value) * 0.01 : 0.025;
        $pmhSaleComission = $reservation->price * $saleComissionPercentage;
        $amountEarned = $reservation->price - $pmhSaleComission;
        Transaction::factory()->earning($amountEarned)->create(['user_id' => $seller->id]);
    }
}
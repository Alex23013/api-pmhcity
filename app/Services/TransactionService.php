<?php
namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Parameter;
use App\Models\Reservation;
use Carbon\Carbon;

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

    public function monthlyEarnings($userId, $year, $month)
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::create($year, $month, 1)->endOfMonth();

        return Transaction::where('user_id', $userId)
            ->where('type', 'earning')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');
    }
}
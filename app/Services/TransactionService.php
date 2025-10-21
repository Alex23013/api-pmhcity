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
        $saleComissionPercentage = $saleComissionPercentage ? ($saleComissionPercentage->value) * 0.01 : 0.02;
        $pmhSaleComission = $reservation->price * $saleComissionPercentage;
        $amountEarned = $reservation->price - $pmhSaleComission;

        Transaction::create([
            'user_id' => $seller->id,
            'type' => 'earning',
            'amount' => $amountEarned,
            'reference_id' => $reservation->id,
            'reference_type' => Reservation::class,
        ]);

        $this->createPmhCityCommisionEarning($reservation, $pmhSaleComission);
    }

    public function createPmhCityCommisionEarning(Reservation $reservation, $pmhSaleComission)
    {
        Transaction::create([
            'user_id' => 1,
            'type' => 'pmh_city_commission',
            'amount' => $pmhSaleComission,
            'reference_id' => $reservation->id,
            'reference_type' => Reservation::class,
        ]);
    }

    public function createPMHRelayCommisionEarning(Reservation $reservation)
    {
        $deliveryPrice = round(Parameter::where('name', 'pmh_relay_delivery_price')->first()?->value, 2);

        Transaction::create([
            'user_id' => 1,
            'type' => 'pmh_relay_commission',
            'amount' => $deliveryPrice,
            'reference_id' => $reservation->id,
            'reference_type' => Reservation::class,
        ]);
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
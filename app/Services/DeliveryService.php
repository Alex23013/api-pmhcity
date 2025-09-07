<?php
namespace App\Services;

use App\Models\Reservation;
use App\Models\Delivery;

class DeliveryService
{
    public function createDelivery(Reservation $reservation, array $data): Delivery
    {
        return $reservation->delivery()->create($data);
    }
}
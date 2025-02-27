<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Reservation;
use App\Models\ReservationStatus;

class ReservationStep extends Model
{
    use HasFactory;

    protected $fillable = ['reservation_id', 'reservation_status_id'];
    protected $hidden = ['id', 'reservation_status_id', 'reservation_id', 'updated_at'];


    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function reservationStatus()
    {
        return $this->belongsTo(ReservationStatus::class);
    }
}

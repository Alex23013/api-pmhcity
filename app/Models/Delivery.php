<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'recipient',
        'address',
        'contact_phone',
        'delivery_code',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}

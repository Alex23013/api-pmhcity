<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ReservationDetail;
use App\Models\Product;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['buyer_id', 'seller_id','product_id', 'last_status','phone','comment'];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function reservationDetails()
    {
        return $this->hasMany(ReservationDetail::class);
    }
}

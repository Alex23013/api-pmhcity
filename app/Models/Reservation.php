<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ReservationStep;
use App\Models\Product;
use App\Models\Size;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['buyer_id', 'seller_id','product_id', 'last_status','phone','comment', 'size_id', 'quantity'];

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

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function reservationSteps()
    {
        return $this->hasMany(ReservationStep::class)->select('id', 'created_at', 'reservation_status_id', 'reservation_id')->with('reservationStatus:id,name,display_name,author');
    }
}

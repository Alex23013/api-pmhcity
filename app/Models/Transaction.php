<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'reference_type',
        'reference_id',
    ];

    // Belongs to a user (seller)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Polymorphic relation (can reference Reservation or Withdrawal)
    public function reference()
    {
        return $this->morphTo();
    }
}

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

    // --- Domain helpers ---
    public static function earning(float $amount, $reference = null, int $userId = null)
    {
        return self::create([
            'user_id'       => $userId ?? ($reference?->user_id ?? null),
            'type'          => 'earning',
            'amount'        => $amount,
            'reference_type'=> $reference ? get_class($reference) : null,
            'reference_id'  => $reference?->id,
        ]);
    }

    public static function withdrawal(float $amount, $reference = null, int $userId = null)
    {
        return self::create([
            'user_id'       => $userId ?? ($reference?->user_id ?? null),
            'type'          => 'withdrawal',
            'amount'        => -abs($amount), // always negative
            'reference_type'=> $reference ? get_class($reference) : null,
            'reference_id'  => $reference?->id,
        ]);
    }
}

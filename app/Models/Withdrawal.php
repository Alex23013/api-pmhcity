<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'method',
        'iban',
        'operation_code',
        'notes',
        'requested_at',
        'processed_at',
    ];

    // Belongs to a user (seller)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Transactions linked to this withdrawal
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'reference');
    }

    protected static function booted()
    {
        static::updated(function ($withdrawal) {
            // Only trigger when withdrawal is paid
            if (in_array($withdrawal->status, ['paid'])) {
                // Ensure no duplicate transaction
                $exists = Transaction::where('reference_type', self::class)
                    ->where('reference_id', $withdrawal->id)
                    ->exists();

                if (! $exists) {
                    Transaction::create([
                        'user_id'        => $withdrawal->user_id,
                        'amount'         => -$withdrawal->amount,
                        'type'           => 'withdrawal',
                        'reference_type' => self::class,
                        'reference_id'   => $withdrawal->id,
                    ]);
                }
            }
        });
    }
}

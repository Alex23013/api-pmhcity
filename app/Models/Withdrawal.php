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
}

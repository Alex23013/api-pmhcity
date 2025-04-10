<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'siret',
        'is_verified',
        'logo',
        'banner',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}

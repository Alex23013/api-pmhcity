<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhoneToken extends Model
{
    use HasFactory;

    protected $fillable = ['phone_number', 'token', 'expires_at', 'is_verified'];

}

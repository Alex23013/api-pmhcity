<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailToken extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'token', 'expires_at', 'is_verified'];
}

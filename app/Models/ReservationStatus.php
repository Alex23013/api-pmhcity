<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ReservationStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'display_name', 'author'];
}

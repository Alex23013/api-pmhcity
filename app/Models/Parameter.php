<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parameter extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'display_name', 'description', 'value'];
}

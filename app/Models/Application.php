<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'lastname', 'phone', 'email', 'store_name', 'store_location', 'store_url', 'store_description', 'admin_notes', 'category', 'status'];
}

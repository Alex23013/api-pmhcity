<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Metropole;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'metropole_id'];

    public function metropole()
    {
        return $this->belongsTo(Metropole::class);
    }
}

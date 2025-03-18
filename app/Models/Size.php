<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SizeType;

class Size extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function sizeType()
    {
        return $this->belongsTo(SizeType::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product;

class PhotoProduct extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product;
use App\Models\Category;
use App\Models\SizeType;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id', 'cover_image'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function sizeType()
    {
        return $this->belongsTo(SizeType::class);
    }
}

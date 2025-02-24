<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PhotoProduct;
use App\Models\Category;
use App\Models\Subcategory;
class Product extends Model
{
    use HasFactory;
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
     protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'category_id',
        'subcategory_id',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function photoProducts()
    {
        return $this->hasMany(PhotoProduct::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
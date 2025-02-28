<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PhotoProduct;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Material;
use App\Models\Brand;
use App\Models\StatusProduct;
use App\Models\Size;
use App\Models\Color;
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
        'material_id',
        'brand_id',
        'status_product_id'
    ];

    protected $casts = [
        'size_ids' => 'array',
        'color_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'product_id');
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

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function status_product()
    {
        return $this->belongsTo(StatusProduct::class);
    }

    public function getSizeIdsAttribute($value)
    {
        $sizeIds = json_decode($value, true);
        if (is_array($sizeIds) && count($sizeIds) > 0) {
            return Size::whereIn('id', $sizeIds)->get(['id', 'name'])->toArray();
        }
        return [];
    }

    public function getColorIdsAttribute($value)
    {
        $colorIds = json_decode($value, true);
        if (is_array($colorIds) && count($colorIds) > 0) {
            return Color::whereIn('id', $colorIds)->get(['id', 'name', 'hex_code'])->toArray();
        }
        return [];
    }
}
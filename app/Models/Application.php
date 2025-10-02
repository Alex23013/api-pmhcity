<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;

class Application extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'lastname', 'phone', 'email', 'store_name', 'store_location', 'store_url', 'store_description', 'admin_notes', 'category', 'status'];

    public function getCategoryNamesAttribute()
    {
        if (!$this->category) {
            return [];
        }
        $ids = array_filter(explode(',', $this->category));
        return Category::whereIn('id', $ids)->pluck('name')->toArray();
    }
}

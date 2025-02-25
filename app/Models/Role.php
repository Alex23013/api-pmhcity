<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Role extends Model
{
    use HasFactory;
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
     protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->hasMany(User::class,'user_id');
    }
}

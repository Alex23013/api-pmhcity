<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Product;
use App\Models\Role;
use App\Models\Store;
use App\Models\City;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'role_id',
        'profile_status',
        'city_id',
        'profile_picture',
        'phone',
        'forget_password_token',
        'fpt_expires_at',
    ];
            
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class,'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }

    public function store()
    {
        return $this->hasOne(Store::class, 'user_id');
    }

    // Reservations the user is selling
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'seller_id');
    }

    // Transactions (earnings, withdrawals, etc.)
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Withdrawals requested by this seller
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    // Seller’s current balance
    public function balance()
    {
        return $this->transactions()->sum('amount');
    }
}

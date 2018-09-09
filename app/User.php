<?php

namespace App;

use App\Models\Product;
use App\Models\Rental;
use App\Models\WithdrawRecords;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'b_user_id');
    }

    /**
     * join rentals
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function joinRentals()
    {
        return $this->hasMany(Rental::class, 'a_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function withdrawRecords()
    {
        return $this->hasMany(WithdrawRecords::class, 'user_id');
    }

}

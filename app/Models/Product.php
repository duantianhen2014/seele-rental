<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $table = 'products';

    protected $fillable = [
        'title', 'description', 'charge', 'deposit',
        'user_id', 'address',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getChargeAttribute($value)
    {
        return $value / Rental::F;
    }

    public function getDepositAttribute($value)
    {
        return $value / Rental::F;
    }

    public function setChargeAttribute($value)
    {
        $this->attributes['charge'] = $value * Rental::F;
    }

    public function setDepositAttribute($value)
    {
        $this->attributes['deposit'] = $value * Rental::F;
    }

}

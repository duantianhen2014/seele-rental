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

}

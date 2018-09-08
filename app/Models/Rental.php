<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{

    const STATUS_A_APPLY = 1;
    const STATUS_B_CONFIRM = 5;
    const STATUS_A_CONFIRM = 10;
    const STATUS_A_COMPLETE = 15;
    const STATUS_COMPLETE = 20;
    const STATUS_REJECT = 99;

    protected $table = 'rentals';

    protected $fillable = [
        'product_id', 'a_user_id', 'b_user_id', 'a_address', 'b_address',
        'status', 'charge', 'deposit', 'reject_reason',
    ];

    public function aUser()
    {
        return $this->belongsTo(User::class, 'a_user_id');
    }

    public function bUser()
    {
        return $this->belongsTo(User::class, 'b_user_id');
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'product_id');
    }

}

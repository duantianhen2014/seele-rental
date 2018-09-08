<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HashResult extends Model
{

    protected $table = 'hash_results';

    protected $fillable = [
        'tx_hash', 'result',
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HashResult extends Model
{

    const REQUEST_TYPE_APPLY = 'Apply';
    const REQUEST_TYPE_B_CONFIRM = 'BConfirm';
    const REQUEST_TYPE_A_CONFIRM = 'AConfirm';
    const REQUEST_TYPE_A_COMPLETE = 'AComplete';
    const REQUEST_TYPE_B_COMPLETE = 'BComplete';

    protected $table = 'hash_results';

    protected $casts = [
        'request_data' => 'array',
    ];

    protected $fillable = [
        'tx_hash', 'result', 'request_data', 'request_type',
    ];

}

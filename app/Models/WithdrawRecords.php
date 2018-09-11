<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class WithdrawRecords extends Model
{

    const STATUS_SUBMIT = 1;
    const STATUS_FAILED = 5;
    const STATUS_SUCCESS = 9;

    protected $table = 'withdraw_records';

    protected $fillable = [
        'user_id', 'before_balance', 'money', 'status',
        'tx_hash',
    ];

    public function getMoneyAttribute($value)
    {
        return $value / Rental::F;
    }

    public function setMoneyAttribute($value)
    {
        $this->attributes['money'] = $value * Rental::F;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return string
     */
    public function statusText()
    {
        if ($this->status == self::STATUS_SUBMIT) {
            return 'submit';
        } else if ($this->status == self::STATUS_FAILED) {
            return 'failed';
        } else if ($this->status == self::STATUS_SUCCESS) {
            return 'success';
        }
    }

}

<?php

namespace App\Models;

use App\Notifications\ACompleteNotification;
use App\Notifications\AConfirmNotification;
use App\Notifications\BCompleteNotification;
use App\Notifications\BConfirmNotification;
use App\Notifications\NewRentalNotification;
use App\Notifications\WithdrawSuccessNotification;
use App\Seele\Seele;
use App\Seele\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class HashResult extends Model
{

    const REQUEST_TYPE_APPLY = 'Apply';
    const REQUEST_TYPE_B_CONFIRM = 'BConfirm';
    const REQUEST_TYPE_A_CONFIRM = 'AConfirm';
    const REQUEST_TYPE_A_COMPLETE = 'AComplete';
    const REQUEST_TYPE_B_COMPLETE = 'BComplete';
    const REQUEST_TYPE_WITHDRAW = 'Withdraw';

    protected $table = 'hash_results';

    protected $casts = [
        'request_data' => 'array',
    ];

    protected $fillable = [
        'tx_hash', 'result', 'request_data', 'request_type', 'user_id',
    ];

    public function handlerApply()
    {
        $data = $this->request_data;
        $product = Product::findOrFail($data['product_id']);
        try {
            $aAddress = $data['row']['Data']['From'];
            $rentalRecord = (new Seele(new User($aAddress, '')))->queryContract();
            Log::info($rentalRecord);

            $rental = Rental::create([
                'a_user_id' => $data['user_id'],
                'product_id' => $product->id,
                'b_user_id' => $product->user->id,
                'a_address' => $aAddress,
                'b_address' => $product->address,
                'status' => Rental::STATUS_A_APPLY,
                'charge' => $rentalRecord['charge'],
                'deposit' => $rentalRecord['deposit'],
                'a_apply_tx_hash' => $data['row']['Hash'],
            ]);

            $product->user->notify(new NewRentalNotification($rental));

        } catch (Exception $exception) {
            Log::info($exception);
        }
    }

    public function handlerBConfirm()
    {
        $data = $this->request_data;
        $hash = $data['row']['Hash'];
        try {
            $rental = Rental::where('b_confirm_tx_hash', $hash)->firstOrFail();

            $rentalRecord = (new Seele(new User($rental->a_address, '')))->queryContract();
            Log::info($rentalRecord);

            $rental->charge = $rentalRecord['charge'];
            $rental->deposit = $rentalRecord['deposit'];
            $rental->status = Rental::STATUS_B_CONFIRM;
            $rental->save();

            $rental->aUser->notify(new BConfirmNotification($rental));

        } catch (Exception $exception) {
            Log::info($exception);
        }
    }

    public function handlerAConfirm()
    {
        $data = $this->request_data;
        $hash = $data['row']['Hash'];
        try {
            $rental = Rental::where('a_confirm_tx_hash', $hash)->firstOrFail();

            $rental->status = Rental::STATUS_A_CONFIRM;
            $rental->save();

            $rental->bUser->notify(new AConfirmNotification($rental));
        } catch (Exception $exception) {
            Log::info($exception);
        }
    }

    public function handlerAComplete()
    {
        $data = $this->request_data;
        $hash = $data['row']['Hash'];
        try {
            $rental = Rental::where('a_complete_apply_tx_hash', $hash)->firstOrFail();

            $rental->status = Rental::STATUS_A_COMPLETE;
            $rental->save();

            $rental->bUser->notify(new ACompleteNotification($rental));
        } catch (Exception $exception) {
            Log::info($exception);
        }
    }

    public function handlerBComplete()
    {
        $data = $this->request_data;
        $hash = $data['row']['Hash'];
        try {
            $rental = Rental::where('b_complete_tx_hash', $hash)->firstOrFail();

            $rental->status = Rental::STATUS_COMPLETE;
            $rental->save();

            $rental->aUser->notify(new BCompleteNotification($rental));
            $rental->bUser->notify(new BCompleteNotification($rental));
        } catch (Exception $exception) {
            Log::info($exception);
        }
    }

    public function handlerWithdraw()
    {
        $withdrawRecord = WithdrawRecords::whereTxHash($this->tx_hash)->first();
        if (!$withdrawRecord) {
            return;
        }
        $withdrawRecord->status = WithdrawRecords::STATUS_SUCCESS;
        $withdrawRecord->save();

        $withdrawRecord->user->notify(new WithdrawSuccessNotification());
    }

}

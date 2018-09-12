<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberWithdrawRequest;
use App\Models\HashResult;
use App\Models\WithdrawRecords;
use App\Seele\User;
use Exception;
use App\Http\Requests\MemberChangePasswordRequest;
use App\Seele\Seele;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{

    public function showWithdrawPage()
    {
        return view('member.withdraw', compact('balance'));
    }

    public function withdrawHandler(MemberWithdrawRequest $request)
    {
        $money = $request->post('money', 0);
        $address = $request->post('address');
        $privateKey = $request->post('private_key');

        if ($money <= 0) {
            flash()->error('please input effective number.');
            return back();
        }

        $balance = (new Seele(new User($address, '')))->queryBalance();
        if ($money > $balance) {
            flash()->error('insufficient balance.');
            return back();
        }

        DB::beginTransaction();
        try {
            $seele = new Seele(new User($address, $privateKey));
            $data = $seele->withdraw($money);

            // Hash record
            HashResult::create([
                'user_id' => Auth::id(),
                'tx_hash' => $data['Hash'],
                'result' => '',
                'request_data' => ['row' => $data],
                'request_type' => HashResult::REQUEST_TYPE_WITHDRAW,
            ]);

            // Create Record
            Auth::user()->withdrawRecords()->save(new WithdrawRecords([
                'before_balance' => $balance,
                'money' => $money,
                'address' => $address,
                'status' => WithdrawRecords::STATUS_SUBMIT,
                'tx_hash' => $data['Hash'],
            ]));

            DB::commit();
            flash()->success('apply has submit.please wait.');
            return back();
        } catch (Exception $exception) {
            DB::rollBack();
            flash()->error($exception->getMessage());
            return back();
        }
    }

    public function showBalance(Request $request)
    {
        $address = $request->get('address');
        $balance = 0;
        if ($address) {
            $balance = (new Seele(new User($address, '')))->queryBalance();
        }
        $withdrawRecords = Auth::user()->withdrawRecords()->orderByDesc('updated_at')->paginate(8);
        return view('member.balance', compact('balance', 'withdrawRecords'));
    }

    public function showChangePasswordPage()
    {
        return view('member.change_password');
    }

    public function changePasswordHandler(MemberChangePasswordRequest $request)
    {
        $user = Auth::user();
        if (!Hash::check($request->post('old_password'), $user->password)) {
            flash()->error('old password error.');
            return back();
        }

        $user->password = bcrypt($request->post('new_password'));
        $user->save();

        flash()->success('success');
        return back();
    }

    public function products()
    {
        $products = Auth::user()->products()->orderByDesc('created_at')->paginate(10);
        return view('member.products', compact('products'));
    }

    public function rentals()
    {
        $rentals = Auth::user()->rentals()->orderByDesc('created_at')->paginate(10);
        return view('member.rentals', compact('rentals'));
    }

    public function joinRentals()
    {
        $rentals = Auth::user()->joinRentals()->orderByDesc('created_at')->paginate(10);
        return view('member.join_rentals', compact('rentals'));
    }

    public function showNotificationPage()
    {
        $notifications = Auth::user()->notifications;
        $notifications = new Paginator($notifications, 10);
        return view('member.notification', compact('notifications'));
    }

}

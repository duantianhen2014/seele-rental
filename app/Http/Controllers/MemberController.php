<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberChangePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{

    public function showBalance()
    {
        $balance = 0;
        return view('member.balance', compact('balance'));
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

}

<?php

namespace App\Http\Controllers;

use App\Models\HashResult;
use App\Seele\Seele;
use App\Seele\User;
use Exception;
use App\Models\Product;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{

    public function show($id)
    {
        $rental = Rental::findOrFail($id);
        return view('rental.show', compact('rental'));
    }

    public function showApplyPage($productId)
    {
        $product = Product::findOrFail($productId);
        return view('rental.apply', compact('product'));
    }

    public function applyHandler(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $privateKey = $request->input('private_key', '');
        $charge = $request->input('charge', 0);

        if (Auth::user()->hasSubmitRental()) {
            flash()->error('please don\'t repeat submit.');
            return back();
        }

        if (Auth::id() == $product->user_id) {
            flash()->error('you has this product.');
            return back();
        }

        try {
            $seele = new Seele(new User('', $privateKey));
            $data = $seele->apply($product->address, $charge);

            // record
            HashResult::create([
                'user_id' => Auth::id(),
                'tx_hash' => $data['Hash'],
                'result' => '',
                'request_data' => [
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'row' => $data,
                ],
                'request_type' => HashResult::REQUEST_TYPE_APPLY,
            ]);

            flash()->success('apply has submit.please wait.');
            return back();
        } catch (Exception $exception) {
            flash()->error($exception->getMessage());
            return back();
        }
    }

    public function showBConfirmPage($rentalId)
    {
        $rental = Rental::findOrFail($rentalId);
        return view('rental.b_confirm', compact('rental'));
    }

    public function bConfirmHandler(Request $request, $rentalId)
    {
        $rental = Rental::findOrFail($rentalId);

        $charge = $request->input('charge', 0);
        $deposit = $request->input('deposit', 0);
        $privateKey = $request->input('private_key', '');
        $agree = $request->input('agree', 0);
        $rejectReason = $request->input('reject_reason', '');

        if ($rental->b_confirm_tx_hash) {
            flash()->error('please don\'t repeat submit.');
            return back();
        }

        DB::beginTransaction();
        try {
            if (!$agree) {
                $rental->status = Rental::STATUS_REJECT;
                $rental->reject_reason = $rejectReason;
            } else {
                $seele = new Seele(new User($rental->b_address, $privateKey));
                $data = $seele->bConfirm($rental->a_address, $charge, $deposit, $agree);

                // record
                HashResult::create([
                    'user_id' => Auth::id(),
                    'tx_hash' => $data['Hash'],
                    'result' => '',
                    'request_data' => ['row' => $data],
                    'request_type' => HashResult::REQUEST_TYPE_B_CONFIRM,
                ]);

                $rental->b_confirm_tx_hash = $data['Hash'];
            }

            $rental->deposit = $deposit;
            $rental->save();

            DB::commit();
            flash()->success('apply has submit.please wait.');
            return redirect(route('rental.show', $rental));
        } catch (Exception $exception) {
            DB::rollBack();
            flash()->error($exception->getMessage());
            return back();
        }
    }

    public function showAConfirmPage($rentalId)
    {
        $rental = Rental::findOrFail($rentalId);
        return view('rental.a_confirm', compact('rental'));
    }

    public function aConfirmHandler(Request $request, $rentalId)
    {
        $rental = Rental::findOrFail($rentalId);

        $agree = $request->input('agree', false);
        $rejectReason = $request->input('reject_reason', '');
        $address = $request->input('address', '');
        $privateKey = $request->input('private_key', '');

        if ($rental->a_confirm_tx_hash) {
            flash()->error('please don\'t repeat submit.');
            return back();
        }

        DB::beginTransaction();
        try {
            if (!$agree) {
                $rental->status = Rental::STATUS_REJECT;
                $rental->reject_reason = $rejectReason;
                $rental->save();
            } else {
                $seele = new Seele(new User($address, $privateKey));
                $data = $seele->aConfirm((bool) $agree);

                // record
                HashResult::create([
                    'user_id' => Auth::id(),
                    'tx_hash' => $data['Hash'],
                    'result' => '',
                    'request_data' => ['row' => $data],
                    'request_type' => HashResult::REQUEST_TYPE_A_CONFIRM,
                ]);

                $rental->a_confirm_tx_hash = $data['Hash'];
                $rental->save();
            }

            DB::commit();
            flash()->success('apply has submit.please wait.');
            return redirect(route('rental.show', $rental));
        } catch (Exception $exception) {
            DB::rollBack();
            flash()->error($exception->getMessage());
            return back();
        }
    }

    public function showACompletePage($rentalId)
    {
        $rental = Rental::findOrFail($rentalId);
        return view('rental.a_complete', compact('rental'));
    }

    public function aCompleteHandler(Request $request, $rentalId)
    {
        $rental = Rental::findOrFail($rentalId);
        $privateKey = $request->input('private_key', '');

        if ($rental->a_complete_apply_tx_hash) {
            flash()->error('please don\'t repeat submit.');
            return back();
        }

        DB::beginTransaction();
        try {
            $seele = new Seele(new User($rental->a_address, $privateKey));
            $data = $seele->aComplete();

            // record
            HashResult::create([
                'user_id' => Auth::id(),
                'tx_hash' => $data['Hash'],
                'result' => '',
                'request_data' => ['row' => $data],
                'request_type' => HashResult::REQUEST_TYPE_A_COMPLETE,
            ]);

            $rental->a_complete_apply_tx_hash = $data['Hash'];
            $rental->save();

            DB::commit();
            flash()->success('apply has submit.please wait.');
            return redirect(route('rental.show', $rental));
        } catch (Exception $exception) {
            DB::rollBack();
            flash()->error($exception->getMessage());
            return back();
        }
    }

    public function showBCompletePage($rentalId)
    {
        $rental = Rental::findOrFail($rentalId);
        return view('rental.b_complete', compact('rental'));
    }

    public function bCompleteHandler(Request $request, $rentalId)
    {
        $rental = Rental::findOrFail($rentalId);
        $privateKey = $request->input('private_key', '');

        if ($rental->b_complete_tx_hash) {
            flash()->error('please don\'t repeat submit.');
            return back();
        }

        DB::beginTransaction();
        try {
            $seele = new Seele(new User($rental->b_address, $privateKey));
            $data = $seele->bComplete($rental->a_address);

            // record
            HashResult::create([
                'user_id' => Auth::id(),
                'tx_hash' => $data['Hash'],
                'result' => '',
                'request_data' => ['row' => $data],
                'request_type' => HashResult::REQUEST_TYPE_B_COMPLETE,
            ]);

            $rental->b_complete_tx_hash = $data['Hash'];
            $rental->save();

            DB::commit();
            flash()->success('apply has submit.please wait.');
            return redirect(route('rental.show', $rental));
        } catch (Exception $exception) {
            DB::rollBack();
            flash()->error($exception->getMessage());
            return back();
        }
    }
}

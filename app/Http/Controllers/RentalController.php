<?php

namespace App\Http\Controllers;

use App\Models\HashResult;
use App\Seele\Seele;
use App\Seele\User;
use Exception;
use App\Models\Product;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{

    public function showApplyPage($productId)
    {
        $product = Product::findOrFail($productId);
        return view('rental.apply', compact('product'));
    }

    public function applyHandler(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $address = $request->post('address', '');
        $privateKey = $request->post('private_key', '');
        $charge = $request->post('charge', 0);

        $seele = new Seele(new User($address, $privateKey));

        try {
            $data = $seele->apply($address, $charge);

            // record
            HashResult::create([
                'tx_hash' => $data['Hash'],
                'request_data' => [
                    'product_id' => $product->id,
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

        $charge = $request->post('charge', 0);
        $deposit = $request->post('deposit', 0);

        $address = $request->post('address', '');
        $privateKey = $request->post('private_key', '');

        $agree = $request->post('agree', false);
        $rejectReason = $request->post('reject_reason', '');

        DB::beginTransaction();
        try {

            if (!$agree) {
                $rental->status = Rental::STATUS_REJECT;
                $rental->reject_reason = $rejectReason;
                $rental->save();
            }

            $seele = new Seele(new User($address, $privateKey));

            $data = $seele->bConfirm($address, $charge, $deposit, $agree);

            // record
            HashResult::create([
                'tx_hash' => $data['Hash'],
                'request_data' => ['row' => $data],
                'request_type' => HashResult::REQUEST_TYPE_A_CONFIRM,
            ]);

            DB::commit();

            flash()->success('apply has submit.please wait.');
            return back();
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

        $agree = $request->post('agree', false);
        $rejectReason = $request->post('reject_reason', '');

        $address = $request->post('address', '');
        $privateKey = $request->post('private_key', '');

        if (!$agree) {
            $rental->status = Rental::STATUS_REJECT;
            $rental->reject_reason = $rejectReason;
            $rental->save();
        }

        $seele = new Seele(new User($address, $privateKey));

        try {
            $data = $seele->aConfirm($agree ? 1 : 0);

            // record
            HashResult::create([
                'tx_hash' => $data['Hash'],
                'request_data' => ['row' => $data],
                'request_type' => HashResult::REQUEST_TYPE_B_CONFIRM,
            ]);

            flash()->success('apply has submit.please wait.');
            return back();
        } catch (Exception $exception) {
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
        $privateKey = $request->post('private_key', '');

        $seele = new Seele(new User($rental->a_address, $privateKey));

        try {
            $data = $seele->aComplete();

            // record
            HashResult::create([
                'tx_hash' => $data['Hash'],
                'request_data' => ['row' => $data],
                'request_type' => HashResult::REQUEST_TYPE_A_COMPLETE,
            ]);

            flash()->success('apply has submit.please wait.');
            return back();
        } catch (Exception $exception) {
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
        $privateKey = $request->post('private_key', '');

        $seele = new Seele(new User($rental->b_address, $privateKey));

        try {
            $data = $seele->bComplete($rental->a_address);

            // record
            HashResult::create([
                'tx_hash' => $data['Hash'],
                'request_data' => ['row' => $data],
                'request_type' => HashResult::REQUEST_TYPE_B_COMPLETE,
            ]);

            flash()->success('apply has submit.please wait.');
            return back();
        } catch (Exception $exception) {
            flash()->error($exception->getMessage());
            return back();
        }
    }
}

@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <p class="text-center">Rental Information</p>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <td>A Address: {{$rental->a_address}}</td>
                                <td>B Address: {{$rental->b_address}}</td>
                                <td>
                                    @if($rental->product->user_id == Auth::id())

                                        @if($rental->status == \App\Models\Rental::STATUS_A_APPLY && !$rental->b_confirm_tx_hash)
                                            <a href="{{route('rental.b_confirm', $rental)}}">You Should be Confirm</a>
                                        @elseif($rental->status == \App\Models\Rental::STATUS_B_CONFIRM)
                                            <span>Wait A Confirm</span>
                                        @elseif($rental->status == \App\Models\Rental::STATUS_A_CONFIRM)
                                            <span>Wait A Complete</span>
                                        @elseif($rental->status == \App\Models\Rental::STATUS_A_COMPLETE && !$rental->b_complete_tx_hash)
                                            <a href="{{route('rental.b_complete', $rental)}}">You Should Be Complete</a>
                                        @elseif($rental->status == \App\Models\Rental::STATUS_COMPLETE)
                                            <span>Complete</span>
                                        @elseif($rental->status == \App\Models\Rental::STATUS_REJECT)
                                            <span>Reject.Reason:{{$rental->reject_reason}}</span>
                                        @endif

                                    @else

                                        @if($rental->status == \App\Models\Rental::STATUS_A_APPLY)
                                            <span>Wait B Confirm</span>
                                        @elseif($rental->status == \App\Models\Rental::STATUS_B_CONFIRM && !$rental->a_confirm_tx_hash)
                                            <a href="{{route('rental.a_confirm', $rental)}}">You Should be Confirm</a>
                                        @elseif($rental->status == \App\Models\Rental::STATUS_A_CONFIRM && !$rental->a_complete_apply_tx_hash)
                                            <a href="{{route('rental.a_complete', $rental)}}">Complete Apply</a>
                                        @elseif($rental->status == \App\Models\Rental::STATUS_A_COMPLETE)
                                            <span>WAIT B CONFIRM</span>
                                        @elseif($rental->status == \App\Models\Rental::STATUS_COMPLETE)
                                            <span>Complete</span>
                                        @elseif($rental->status == \App\Models\Rental::STATUS_REJECT)
                                            <span>Reject.Reason:{{$rental->reject_reason}}</span>
                                        @endif

                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Charge: {{$rental->charge}} seele</td>
                                <td>Deposit Money: {{$rental->deposit}} seele</td>
                                <td>{{$rental->statusText()}}</td>
                            </tr>
                            @if($rental->a_apply_tx_hash)
                                <tr><td colspan="3">A Apply Hash:{{$rental->a_apply_tx_hash}}</td></tr>
                            @endif
                            @if($rental->b_confirm_tx_hash)
                                <tr>
                                    <td colspan="3">
                                        B Confirm Hash:{{$rental->b_confirm_tx_hash}}
                                    </td>
                                </tr>
                            @endif
                            @if($rental->a_confirm_tx_hash)
                                <tr>
                                    <td colspan="3">
                                        A Confirm Hash:{{$rental->a_confirm_tx_hash}}
                                    </td>
                                </tr>
                            @endif
                            @if($rental->a_complete_apply_tx_hash)
                                <tr>
                                    <td colspan="3">
                                        A Complete Hash:{{$rental->a_complete_apply_tx_hash}}
                                    </td>
                                </tr>
                            @endif
                            @if($rental->b_complete_tx_hash)
                                <tr>
                                    <td colspan="3">
                                        B Complete Hash:{{$rental->b_complete_tx_hash}}
                                    </td>
                                </tr>
                            @endif
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
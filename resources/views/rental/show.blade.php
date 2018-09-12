@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <p class="text-center">Rental Information</p>
                    </div>
                    <div class="panel-body">
                        <p>A Address: {{$rental->a_address}}</p>
                        <p>B Address: {{$rental->b_address}}</p>
                        <p>Charge: {{$rental->charge}} seele</p>
                        <p>Deposit Money: {{$rental->deposit}} seele</p>
                        <p>Status: {{$rental->statusText()}}</p>
                        @if($rental->a_apply_tx_hash)
                        <p>{{$rental->a_apply_tx_hash}}</p>
                        @endif
                        @if($rental->b_confirm_tx_hash)
                            <p>{{$rental->b_confirm_tx_hash}}</p>
                        @endif
                        @if($rental->a_confirm_tx_hash)
                            <p>{{$rental->a_confirm_tx_hash}}</p>
                        @endif
                        @if($rental->a_complete_apply_tx_hash)
                            <p>{{$rental->a_complete_apply_tx_hash}}</p>
                        @endif
                        @if($rental->b_complete_tx_hash)
                            <p>{{$rental->b_complete_tx_hash}}</p>
                        @endif

                        @if($rental->product->user_id == Auth::id())

                            <p>
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
                            </p>

                            @else

                            <p>
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
                            </p>

                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
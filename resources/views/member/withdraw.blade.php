@extends('layouts.member')

@section('member')

    <h2>Withdraw</h2>

    <div class="alert alert-warning">
        <p>Please recharge to this address:</p>
        <p><b>{{config('seele.contract_address')}}</b></p>
    </div>

    <p>Balance:</p>
    <p class="text-right"> <span class="balance" style="font-size: 52px; font-weight: 800;">{{$balance}}</span> seele</p>

    <div class="col-sm-8 col-sm-offset-2">
        <form action="" class="form-horizontal" method="post">
            {!! csrf_field() !!}
            <div class="form-group">
                <label>Money</label>
                <div class="input-group">
                    <input type="text" name="money" class="form-control" placeholder="withdraw">
                    <div class="input-group-addon">seele</div>
                </div>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" class="form-control" placeholder="Address">
            </div>
            <div class="form-group">
                <label>PrivateKey</label>
                <input type="text" name="private_key" class="form-control" placeholder="PrivateKey">
            </div>

            <div class="form-group">
                <button class="btn btn-primary btn-block">Withdraw</button>
            </div>
        </form>
    </div>

@endsection
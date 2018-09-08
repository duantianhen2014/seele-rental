@extends('layouts.member')

@section('member')

    <div class="alert alert-warning">
        <p>Please recharge to this address:</p>
        <p><b>{{config('seele.contract_address')}}</b></p>
    </div>

    <p>MyBalance:</p>
    <p class="text-right"> <span class="balance" style="font-size: 52px; font-weight: 800;">{{$balance}}</span> seele</p>

@endsection
@extends('layouts.member')

@section('member')

    <div class="alert alert-warning">
        <p>Please recharge to this address:</p>
        <p><b>{{config('seele.contract_address')}}</b></p>
    </div>

    <p>Balance:</p>
    <p class="text-right">
        <span class="balance" style="font-size: 52px; font-weight: 800;">{{$balance}}</span> seele
        <a href="{{route('member.withdraw')}}">Withdraw</a>
    </p>

    <h4>Withdraw Records:</h4>
    <table class="table table-hover">
        <thead>
        <th>ID</th>
        <th>Before Balance</th>
        <th>Withdraw Money</th>
        <th>CreatedAt</th>
        <th>UpdatedAt</th>
        </thead>
        <tbody>
        @forelse($withdrawRecords as $record)
            <tr>
                <td>{{$record->id}}</td>
                <td>{{$record->before_balance}}</td>
                <td>{{$record->money}}</td>
                <td>{{$record->created_at}}</td>
                <td>{{$record->updated_at}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">None.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

@endsection
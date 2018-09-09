@extends('layouts.member')

@section('member')

    <h2>Balance</h2>

    <div class="alert alert-warning">
        <p>Please recharge to this address:</p>
        <p><b>{{config('seele.contract_address')}}</b></p>
    </div>

    <p>Balance:</p>
    <form action="" method="get" class="form-horizontal">
        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" class="form-control">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Query</button>
        </div>
    </form>
    <div class="alert alert-warning">
        <p>Note:</p>
        <p>Below data could not correct,because network delay.</p>
    </div>
    <p class="text-right">
        {{request()->get('address')}}: <span class="balance" style="font-size: 52px; font-weight: 800;">{{$balance}}</span> seele
        <a href="{{route('member.withdraw')}}">Withdraw</a>
    </p>

    <h4>Withdraw Records:</h4>
    <table class="table table-hover">
        <thead>
        <th>ID</th>
        <th>Withdraw Money</th>
        <th>Status</th>
        <th>CreatedAt</th>
        <th>UpdatedAt</th>
        </thead>
        <tbody>
        @forelse($withdrawRecords as $record)
            <tr>
                <td>{{$record->id}}</td>
                <td>{{$record->money}} seele</td>
                <td>{{$record->statusText()}}</td>
                <td>{{$record->created_at}}</td>
                <td>{{$record->updated_at}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">None.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

@endsection
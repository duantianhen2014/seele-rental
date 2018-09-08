@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <h2>{{$product->title}}</h2>
            <p>
                <span>From: &nbsp; {{$product->user->name}}</span> &nbsp;
                <span>UpdatedAt: &nbsp; {{$product->updated_at->diffForHumans()}}</span>
            </p>
            <p>Rental Money: <b>{{ $product->charge }}</b> seele</p>
            <p>Deposit Money: <b>{{ $product->deposit }}</b> seele</p>

            <p>Description:</p>
            <p>{!! nl2br($product->description) !!}</p>

            <p class="text-right">
                <a href="{{route('rental.apply', $product)}}" class="btn btn-primary">Rental</a>
            </p>
        </div>
    </div>

@endsection
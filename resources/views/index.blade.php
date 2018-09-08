@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            @forelse($products as $product)
                <div class="col-sm-3 product-item">
                    <h4>{{$product->title}}</h4>
                    <p>From:{{$product->user->name}}</p>
                    <p>Rental Money: <b>{{$product->charge}}</b> seele</p>
                    <p>Deposit Money: <b>{{$product->deposit}}</b> seele</p>
                    <p class="text-right">
                        <a href="{{route('product.show', $product)}}">Rental</a>
                    </p>
                </div>
                @endforeach

                <div class="col-sm-12 text-center">
                    {{$products->render()}}
                </div>

        </div>
    </div>

@endsection
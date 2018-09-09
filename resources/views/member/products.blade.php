@extends('layouts.member')

@section('member')

    <div>
        <a href="{{route('product.create')}}">Create</a>
    </div>
    
    <table class="table table-hover">
        <thead>
        <th>ID</th>
        <th>Title</th>
        <th>UpdatedAt</th>
        </thead>
        @forelse($products as $product)
        <tr>
            <td>{{$product->id}}</td>
            <td>{{$product->title}}</td>
            <td>{{$product->updated_at->diffForHumans()}}</td>
        </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">
                    None. <a href="{{route('product.create')}}">Create</a>
                </td>
            </tr>
        @endforelse
    </table>

    <div class="text-right">
        {{$products->render()}}
    </div>

@endsection
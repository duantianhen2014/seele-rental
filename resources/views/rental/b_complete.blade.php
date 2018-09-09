@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="text-center">Rental Complete Over.</h3>

                <div class="alert alert-danger">
                    <p>Note:</p>
                    <p>If you click confirm button,you will get rental charge and deposit will return user.</p>
                </div>

                <form action="" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label>Product</label>
                        <input type="text" value="{{$rental->product->title}}" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Charge</label>
                        <input type="text" value="{{$rental->charge}}" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Deposit Money</label>
                        <input type="text" value="{{$rental->deposit}}" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>PrivateKey</label>
                        <input type="text" name="private_key" class="form-control" placeholder="PrivateKey">
                        <span>Address: <b>{{$rental->b_address}}</b></span>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary">Confirm</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection
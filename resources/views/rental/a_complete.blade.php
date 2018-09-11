@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="text-center">Rental Complete Apply</h3>

                <div class="alert alert-warning">
                    <p>Note:</p>
                    <p>If you click confirm button,the charge will transfer to rental owner and deposit will return you.</p>
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
                        <input type="text" name="private_key" class="form-control" placeholder="PrivateKey" required>
                        <span>Address: <b>{{$rental->a_address}}</b></span>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary">Confirm</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection
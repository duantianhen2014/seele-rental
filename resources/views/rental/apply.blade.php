@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="text-center">Rental Apply</h3>

                <form action="" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label>Product</label>
                        <input type="text" value="{{$product->title}}" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" placeholder="Address" required>
                    </div>
                    <div class="form-group">
                        <label>PrivateKey</label>
                        <input type="text" name="private_key" class="form-control" placeholder="PrivateKey" required>
                    </div>
                    <div class="form-group">
                        <label>Charge</label>
                        <div class="input-group">
                            <input type="text" name="charge" class="form-control" value="{{$product->charge}}" required>
                            <div class="input-group-addon">seele</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Confirm Apply</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

@endsection
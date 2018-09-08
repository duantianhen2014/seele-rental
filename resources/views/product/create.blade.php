@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center" style="line-height: 120px;">
                <h3>Product Create</h3>
            </div>

            <div class="col-sm-12">
                <form action="" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="product_title" placeholder="Title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="product_description" class="form-control" placeholder="Description" rows="10" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Rental Charge</label>
                        <input type="text" name="charge" class="form-control" value="0" required>
                    </div>
                    <div class="form-group">
                        <label>Deposit Money</label>
                        <input type="text" name="deposit" class="form-control" value="0" required>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection
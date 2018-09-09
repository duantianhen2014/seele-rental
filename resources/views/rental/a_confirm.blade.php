@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="text-center">Rental Confirm</h3>

                <div class="alert alert-danger">
                    <p>Note:</p>
                    <p>.Please check charge,if you can't accept this price,choose the reject.</p>
                    <p>.Please check deposit,if you can't accept this price,choose the reject.</p>
                </div>

                <form action="" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label>Product</label>
                        <input type="text" value="{{$rental->product->title}}" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Charge</label>
                        <div class="input-group">
                            <input type="text" value="{{$rental->charge}}" class="form-control" readonly>
                            <div class="input-group-addon">seele</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Deposit Money</label>
                        <div class="input-group">
                            <input type="text" value="{{$rental->deposit}}" class="form-control" readonly>
                            <div class="input-group-addon">seele</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>PrivateKey</label>
                        <input type="text" name="private_key" class="form-control" placeholder="PrivateKey" required>
                        <span>Address: <b>{{$rental->a_address}}</b></span>
                    </div>

                    <div class="agree-box"></div>

                    <div class="form-group">
                        <label>Agree</label>
                        <select name="agree" class="form-control">
                            <option value="1">agree</option>
                            <option value="0">disagree</option>
                        </select>
                    </div>

                    <div class="disagree-box" style="display: none">
                        <div class="form-group">
                            <label>Reject Reason</label>
                            <input type="text" name="reject_reason" class="form-control" placeholder="reject reason">
                        </div>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary">Confirm</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(function () {
            $('select[name="agree"]').change(function () {
                if ($(this).val() == 1) {
                    $('.agree-box').show();
                    $('.disagree-box').hide();
                } else {
                    $('.agree-box').hide();
                    $('.disagree-box').show();
                }
            });
        });
    </script>
@endsection
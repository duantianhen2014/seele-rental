@extends('layouts.member')

@section('member')

    <h2>Password Change</h2>

    <form action="" method="post" class="form-horizontal">
        {!! csrf_field() !!}
        <div class="form-group">
            <label>Old Password</label>
            <input type="password" name="old_password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Please input again</label>
            <input type="password" name="new_password_confirmation" class="form-control" required>
        </div>
        <div class="form-group">
            <button class="btn btn-primary">Save</button>
        </div>
    </form>

@endsection
@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <ul class="list-group">
                    <li class="list-group-item">
                        <a href="{{route('home') }}">Dashboard</a>
                    </li>
                    <li class="list-group-item">
                        <a href="{{route('member.balance') }}">Balance</a>
                    </li>
                    <li class="list-group-item">
                        <a href="{{route('member.products') }}">Products</a>
                    </li>
                    <li class="list-group-item">
                        <a href="{{route('home') }}">Orders</a>
                    </li>
                    <li class="list-group-item">
                        <a href="{{route('home') }}">Rentals</a>
                    </li>
                    <li class="list-group-item">
                        <a href="{{route('member.change_password') }}">PasswordChange</a>
                    </li>
                </ul>
            </div>

            <div class="col-sm-9">
                @yield('member')
            </div>
        </div>
    </div>

@endsection
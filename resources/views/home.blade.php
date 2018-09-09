@extends('layouts.member')

@section('member')
    <h2>Dashboard</h2>
    
    <p>Hi.{{Auth::user()->name}}.</p>
    <p>Now,you can rental something by using seele on our platform.</p>
@endsection

@extends('layouts.member')

@section('member')
    <p>Hi.{{Auth::user()->name}}.</p>
    <p>Now,you can rental something by using seele on our platform.</p>
@endsection

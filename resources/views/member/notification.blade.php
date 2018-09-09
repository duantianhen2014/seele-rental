@extends('layouts.member')

@section('member')

    <h2>Notifications</h2>

    <ul>
        @forelse($notifications as $index => $notification)
            <li>
                <b>{{$notification->created_at->diffForHumans()}}:</b>
                @include(get_notification_template_name($notification), ['notification' => $notification])
            </li>
            @if($notification->markAsRead())@endif
            @empty
            <li><p class="text-center" style="color: gray">None.</p></li>
        @endforelse
    </ul>

    <div class="text-right">{{$notifications->render()}}</div>

@endsection
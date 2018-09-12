rental {{$notification->data['type']}} action has error.
error code: {{$notification->data['code']}}.
error message: {{\App\Seele\ErrorMessage::getMessage($notification->data['code'])}}
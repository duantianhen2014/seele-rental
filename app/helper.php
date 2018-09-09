<?php

if (!function_exists('get_notification_template_name')) {
    function get_notification_template_name($notification)
    {
        $name = $notification->type;
        $name = explode('\\', $name);
        $name = $name[count($name) - 1];
        return 'member.notification.'.strtolower($name);
    }
}
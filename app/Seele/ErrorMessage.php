<?php
// +----------------------------------------------------------------------
// | ErrorMessage.php
// +----------------------------------------------------------------------
// | Description: ErrorMessage.php
// +----------------------------------------------------------------------
// | Time: 18-9-12 下午3:12
// +----------------------------------------------------------------------
// | Author: 小滕<616896861@qq.com>
// +----------------------------------------------------------------------

namespace App\Seele;


class ErrorMessage
{

    public static function getMessage($code)
    {
        $s = '';
        switch ($code) {
            case 10001:
                $s = 'insufficient balance.';
                break;
            case 10005:
                $s = 'current can\'t withdraw because has doing order.';
                break;
            case 20001:
                $s = 'current can\'t apply new order because has doing order.';
                break;
            case 30001:
            case 60001:
                $s = 'no auth.';
                break;
            case 30005:
            case 40001:
            case 50001:
            case 60005:
                $s = 'no order need operate.';
                break;
        }
        return $s;
    }

}
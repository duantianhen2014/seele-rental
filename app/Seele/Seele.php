<?php
// +----------------------------------------------------------------------
// | Seele.php
// +----------------------------------------------------------------------
// | Description: Seele.php
// +----------------------------------------------------------------------
// | Time: 18-9-6 下午1:20
// +----------------------------------------------------------------------
// | Author: 小滕<616896861@qq.com>
// +----------------------------------------------------------------------

namespace App\Seele;


class Seele
{

    protected $request;

    public function __construct()
    {
        $this->request = new Request;
    }

    public function getBalance(User $user)
    {
        $this->request->setUser($user);
        dd($this->request->request('27e235e3', $user->address));
    }

}
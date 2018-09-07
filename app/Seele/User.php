<?php
// +----------------------------------------------------------------------
// | User.php
// +----------------------------------------------------------------------
// | Description: User.php
// +----------------------------------------------------------------------
// | Time: 18-9-6 下午2:05
// +----------------------------------------------------------------------
// | Author: 小滕<616896861@qq.com>
// +----------------------------------------------------------------------

namespace App\Seele;


class User
{

    protected $privateKey;

    public $address;

    public function __construct(string $address, string $privateKey = '')
    {
        $this->address = $address;
        $this->privateKey = $privateKey;
    }

    /**
     * @param string $privateKey
     */
    public function setPrivateKey(string $privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

}
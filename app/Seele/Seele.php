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

    protected $user;

    public function __construct(User $user)
    {
        $this->request = new Request;
        $this->user = $user;
        $this->request->setUser($user);
    }

    public function queryBalance()
    {
        [$balance] = $this->request->call('37f42841', $this->user->address);
        return $this->balanceForHumans(hexdec($balance));
    }

    public function queryContract()
    {
        [$address, $charge, $deposit, $aConfirm, $bConfirm, $aCompleteConfirm] = $this->request->call('cb545e4b', $this->user->address);
        $charge = $this->balanceForHumans(hexdec($charge));
        $deposit = $this->balanceForHumans(hexdec($deposit));
        $aConfirm = (bool)hexdec($aConfirm);
        $bConfirm = (bool)hexdec($bConfirm);
        $aCompleteConfirm = (bool)hexdec($aCompleteConfirm);
        return compact('address', 'charge', 'deposit', 'aConfirm', 'bConfirm', 'aCompleteConfirm');
    }

    public function apply($address, $charge)
    {
        $result = $this->request->request('207a7254', $address, dechex($this->balanceForSeele($charge)));
        return $result;
    }

    public function aConfirm(bool $agree)
    {
        $result = $this->request->request('0a7fc6e4', $agree ? 1 : 0);
        return $result;
    }

    public function aComplete()
    {
        return $this->request->request('fe3b79ce');
    }

    public function bConfirm($address, int $charge, int $deposit, bool $agree)
    {
        return $this->request->request(
            'abb5b996',
            $address,
            dechex($this->balanceForSeele($charge)),
            dechex($this->balanceForSeele($deposit)),
            $agree
        );
    }

    public function bComplete($address)
    {
        return $this->request->request('23c7ed3d', $address);
    }

    public function withdraw(int $money)
    {
        return $this->request->request('2e1a7d4d', dechex($this->balanceForSeele($money)));
    }

    public function balanceForHumans($balance)
    {
        return $balance / 100000000;
    }

    public function balanceForSeele($balance)
    {
        return $balance * 100000000;
    }

}
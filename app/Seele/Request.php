<?php
// +----------------------------------------------------------------------
// | Request.php
// +----------------------------------------------------------------------
// | Description: Request.php
// +----------------------------------------------------------------------
// | Time: 18-9-6 下午2:21
// +----------------------------------------------------------------------
// | Author: 小滕<616896861@qq.com>
// +----------------------------------------------------------------------

namespace App\Seele;

use Exception;
use GuzzleHttp\Client;

class Request
{

    public $command;

    public $client;

    public $contractAddress;

    public $user;

    public $amount = 0;

    public $fee;

    public $url;

    public function __construct()
    {
        $this->url = config('seele.url');
        $this->command = config('seele.client_command');
        $this->client = new Client();
        $this->contractAddress = config('seele.contract_address');
        $this->fee = config('seele.fee', 1);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param int $fee
     */
    public function setFee(int $fee)
    {
        $this->fee = $fee;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @param $code
     * @param mixed ...$args
     * @return array
     * @throws Exception
     */
    public function request($code, ...$args)
    {
        $payload = '0x'.$code.$this->payloadEncode($args);
        $data = $this->getRequestParams($payload);

        $response = $this->client->post($this->url, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($data),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new Exception('api request error.');
        }

        $result = json_decode($response->getBody(), true);
        if (isset($result['error'])) {
            throw new Exception($result['error']['message']);
        }

        return $data['params'][0]['Hash'];
    }

    /**
     * @param string $payload
     * @return array
     * @throws Exception
     */
    public function getRequestParams(string $payload)
    {
        $params = [
            'jsonrpc' => '2.0',
            'method' => 'seele.AddTx',
            'params' => [
                $this->generateSignature($payload),
            ],
            'id' => 1,
        ];
        return $params;
    }

    /**
     * 获取交易签名
     * @param string $payload
     * @return array|mixed
     * @throws Exception
     */
    public function generateSignature(string $payload)
    {
        $payload = substr($payload, 0, 2) == '0x' ? $payload : '0x'.$payload;
        $command = sprintf(
            "%s sign -k %s -t %s -m 0 --fee %d --payload %s",
            $this->command,
            $this->user->getPrivateKey(),
            $this->contractAddress,
            $this->fee,
            $payload
        );
        $data = $this->getExecResult($command);
        unset($data[0]);
        $data[1] = '{';
        $data = json_decode(implode('', $data), true);
        return $data;
    }

    /**
     * @param string $hash
     * @return array
     * @throws Exception
     */
    public function queryHash(string $hash)
    {
        $hash = substr($hash, 0, 2) == '0x' ? $hash : '0x'.$hash;
        $command = sprintf("%s getreceipt --hash %s", $this->command, $hash);
        $result = $this->getExecResult($command);
        $result = json_decode(implode('', $result), true);
//        if ($result['failed']) {
//            throw new Exception($result['result']);
//        }
        return $result;
    }

    /**
     * @param $method
     * @param mixed ...$args
     * @return array
     * @throws Exception
     */
    public function call($method, ...$args)
    {
        $payload = '0x'.$method.$this->payloadEncode($args);

        $command = sprintf(
            "%s sign -m 0 -t %s --fee 0 -k %s --payload %s",
            $this->command,
            $this->contractAddress,
            $this->user->getPrivateKey(),
            $payload
        );
        $signResult = $this->getExecResult($command);
        unset($signResult[0]);
        $signResult[1] = '{';
        $sign = json_decode(implode('', $signResult), true);
        $data = [
            'jsonrpc' => '2.0',
            'method' => 'seele.Call',
            'params' => [
                [
                    'Tx' => [
                        'Data' => $sign['Data'],
                    ],
                    'Height' => -1
                ]
            ],
            'id' => 1,
        ];

        $response = $this->client->post($this->url, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($data),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new Exception('api request error.');
        }

        $result = json_decode($response->getBody(), true);
        if (isset($result['error'])) {
            throw new Exception($result['error']['message']);
        }

        return $this->payloadDecode($result['result']['result']);
    }

    /**
     * 获取命令行的执行结果
     * @param $command
     * @return array|mixed
     * @throws Exception
     */
    public function getExecResult($command)
    {
        exec($command, $result, $status);
        if ($status != 0) {
            throw new Exception('get sign error.');
        }
        return $result;
    }

    /**
     * @param array $args
     * @return string
     */
    public function payloadEncode(array $args)
    {
        $payload = '';
        foreach ($args as $arg) {
            $arg = $this->remove0xPrefix($arg);
            $payload .= str_pad('', 64 - mb_strlen($arg), '0').$arg;
        }
        return $payload;
    }

    /**
     * @param string $payload
     * @return array
     */
    public function payloadDecode(string $payload)
    {
        $payload = $this->remove0xPrefix($payload);
        preg_match_all('/[0-9a-z]{64}/', $payload, $rows);
        if (!isset($rows[0]) || !$rows[0]) {
            return [];
        }
        return $rows[0];
    }

    /**
     * @param string $value
     * @return bool|string
     */
    protected function remove0xPrefix(string $value)
    {
        return substr($value, 0, 2) == '0x' ? substr($value, 2) : $value;
    }

}
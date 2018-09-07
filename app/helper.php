<?php
// +----------------------------------------------------------------------
// | help.php
// +----------------------------------------------------------------------
// | Description: help.php
// +----------------------------------------------------------------------
// | Time: 18-9-6 下午2:09
// +----------------------------------------------------------------------
// | Author: 小滕<616896861@qq.com>
// +----------------------------------------------------------------------

if (! function_exists('http_post')) {
    function seele_post($url, $data)
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => $url,
            'timeout' => 1,
        ]);
        $response = $client->post('', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => $data,
        ]);
        if (200 != $response->getStatusCode()) {
            throw new \Exception('接口访问出错');
        }
        $responseData = json_decode($response->getBody(), true);
        if (isset($responseData['error'])) {
            throw new \Exception($responseData['error']['message'], $responseData['error']['code']);
        }
        return $responseData['result'];
    }
}
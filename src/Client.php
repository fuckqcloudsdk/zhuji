<?php

/*
 * This file is part of the bestony/zhuji.
 *
 * (c) Bestony <best.tony@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Bestony\Zhuji;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    protected $client;
    protected $secretId;
    protected $secretKey;
    protected $region;
    protected $version;
    protected $endpoint;

    public function __construct(
        string $secretId,
        string $secretKey,
        string $region = 'ap-shanghai',
        string $version = '2019-01-21',
        string $endpoint = 'https://zj.tencentcloudapi.com'
        ) {
        $this->secretKey = $secretKey;
        $this->secretId = $secretId;
        $this->client = new GuzzleClient();
        $this->region = $region;
        $this->version = $version;
        $this->endpoint = $endpoint;
    }

    public function getRecommendProducts(array $recommedArray)
    {
        $paramArray = [
          'Action' => 'DescribeRecommendProducts',
          'Region' => $this->region,
          'SecretId' => $this->secretId,
          'Nonce' => random_int(1000, 1000000),
          'Timestamp' => time(),
          'Version' => $this->version,
        ];
        $paramArray = array_merge($paramArray, $recommedArray);
        $paramArray['Signature'] = $this->_sign($paramArray, $this->secretKey);
        $response = $this->client->request('POST', $this->endpoint, [
            'form_params' => $paramArray,
          ]);
        $body = $response->getBody();

        return $body->getContents();
    }

    public function createUserAction(array $userAction)
    {
        $paramArray = [
            'Action' => 'CreateUserAction',
            'Region' => $this->region,
            'SecretId' => $this->secretId,
            'Nonce' => random_int(1000, 1000000),
            'Timestamp' => time(),
            'Version' => $this->version,
        ];
        $paramArray = array_merge($paramArray, $userAction);
        $paramArray['Signature'] = $this->_sign($paramArray, $this->secretKey);
        $response = $this->client->request('POST', $this->endpoint, [
          'form_params' => $paramArray,
        ]);
        $body = $response->getBody();

        return $body->getContents();
    }

    /**
     * Generate Sign.
     *
     * @param array $param Param Array
     *
     * @return string param sign
     */
    private function _sign($param)
    {
        $srcStr = $this->_buildString($param);

        return  base64_encode(hash_hmac('sha1', $srcStr, $this->secretKey, true));
    }

    /**
     * Build Request Params.
     *
     * @param array  $requestParams request param
     * @param string $requestMethod requrest method, POST/GET
     *
     * @return string the String of Request Param
     */
    private function _buildString($requestParams, $requestMethod = 'POST')
    {
        $paramStr = '';
        ksort($requestParams);
        $i = 0;
        foreach ($requestParams as $key => $value) {
            if ('Signature' == $key) {
                continue;
            }
            // 排除上传文件的参数
            if ('POST' == $requestMethod && '@' == substr($value, 0, 1)) {
                continue;
            }
            // 把 参数中的 _ 替换成 .
            if (strpos($key, '_')) {
                $key = str_replace('_', '.', $key);
            }
            if (0 == $i) {
                $paramStr .= '?';
            } else {
                $paramStr .= '&';
            }
            $paramStr .= $key.'='.$value;
            ++$i;
        }

        $paramStr = $requestMethod.'zj.tencentcloudapi.com/'.$paramStr;

        return $paramStr;
    }
}

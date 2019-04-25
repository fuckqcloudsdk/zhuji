<h1 align="center"> 腾讯云·珠玑 SDK </h1>

[![Build Status](https://travis-ci.com/bestony/zhuji.svg?branch=master)](https://travis-ci.com/bestony/zhuji)

<p align="center"> 一个基于 PHP 元编程实现的腾讯云·珠玑智能推荐 SDK </p>

## 安装

```shell
$ composer require bestony/zhuji -vvv
```

## 用法

1. 使用 Composer 引入 SDK
2. 使用 SecretID、SecretKey 初始化 Client
3. 构造参数
4. 调用方法发送请求，并获取返回值。

```php

<?php

use Bestony\Zhuji\Client;
$client = new Client('secretId', 'secretKey');
$param = [
    'PoolIds.0' => '推荐组合ID',
    'SceneId' => '场景ID',
    'UidType' => '用户ID类型',
    'Uid' => '用户ID'
];
echo $client->DescribeRecommendProducts($param);
// echo $client->CreateUserAction($param);
// echo $client->ModifyProduct($param);
// echo $client->CreateProduct($param);
// echo $client->CreateProductPool($param);
```

## Client 的参数说明

```php
<?php

public function __construct(
        string $secretId, // SecretID
        string $secretKey, // SecretKey
        string $region = 'ap-shanghai', // Region
        string $version = '2019-01-21', // Version
        string $endpoint = 'https://zj.tencentcloudapi.com' //Endpoint
        )
```

## 贡献项目

你可以以下三种形式来参与项目贡献

1. 使用 [issue](https://github.com/bestony/zhuji/issues) 来记录本项目的 SDK
2. 在 Issue [issue tracker](https://github.com/bestony/zhuji/issues) 中回答问题或修复 Bug。
3. 维护新的功能或更新 Wiki。

## License

MIT

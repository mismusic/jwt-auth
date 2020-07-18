<?php

namespace MisMusic\JWTAuth;

use Illuminate\Support\Facades\Cache;
use MisMusic\JWTAuth\Contracts\JWTManage;
use MisMusic\JWTAuth\Support\Blacklist;
use MisMusic\JWTAuth\Support\JWT;
use MisMusic\JWTAuth\Support\TTL;
use MisMusic\JWTAuth\Support\Utils;

class JWTAuth implements JWTManage
{
    use Blacklist, JWT, TTL;

    protected $header;
    protected $payload;
    protected $blacklist;

    protected $ttl;

    public function __construct(Header $header, Payload $payload, Blacklist $blacklist)
    {
        $this->header = $header;
        $this->payload = $payload;
    }

    public function valid($token)
    {
        // 获取token
        $token = $this->getRequestToken();
        // 验证token
        $this->verifyToken($token);
    }

    public function getToken()
    {
        // 获取token
        $token = $this->getRequestToken();
        // 验证token
        return $this->verifyToken($token);
    }

    public function create(array $claims = [], $flag = null)
    {
        // 生成一个token值
        $time = time();  // 获取当前时间戳
        $expTime = bcadd($time, $this->getTTL());  // 过期时间
        $jti = str_replace('.', '', uniqid('jwt', true));  // 生成一个唯一的标识
        $this->payload->setJti($jti);  // 设置jwt标识
        $this->payload->setIat($time);  // 设置jwt开始时间
        $this->payload->setNbf($time);  // 设置jwt生效时间
        $this->payload->setExp($expTime);  // 设置jwt过期时间
        $this->payload->setClaims($claims);  // 设置用户要存储的自定义数据
        $header = Utils::encoded($this->header->toArray());  // 对头部数据先进行json加密，然后进行base64Url加密
        $payload = Utils::encoded($this->payload->toArray());  // 对载荷数据进行加密
        $algo = $this->header->getAlg();  // 获取头部里面的algo
        $signature = $this->generateSign($header, $payload, $algo);  // 生成签名
        $token = $header . '.' . $payload . '.' . $signature;  // 把头部，载荷，签名组合成为一个token

        // 判断是不是单点登录
        if (! is_null($flag)) {
            $this->singlePointLogin($flag, $token);
        }

        // 返回该token值
        return $token;
    }

    public function refresh($token, $flag = null)
    {
        // 验证token
        $requestToken = $this->verifyToken($token, $flag);
        // 把请求token加入到黑名单
        $this->addBlacklist($requestToken);
        // 生成一个新的token值
        $token = $this->create([], $flag);
        // 返回token值
        return $token;
    }

    public function delete($token)
    {
        // 验证token
        $requestToken = $this->verifyToken($token);
        // 把请求token加入到黑名单
        $this->addBlacklist($requestToken);
    }

}
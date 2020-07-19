<?php

namespace MisMusic\JWTAuth\Support;

use Illuminate\Support\Facades\Cache;
use MisMusic\JWTAuth\Exceptions\JWTException;

trait JWT
{

    use Blacklist;

    public $hmacMapping = [
        'HS256' => 'sha256',
        'HS384' => 'sha384',
        'HS512' => 'sha512',
    ];
    public $singlePointLoginPrefix = 'jwt_single_point_login_';  // 单点登录存储key值的默认前戳
    public $singlePointLoginTTL = 2592000;  // 单点登录缓存的生存时间默认为一个月

    public function getRequestToken()
    {
        if (! $token = request()->headers->get('Authorization')) {
            throw new JWTException('JWT 获取请求token失败');
        }
        return ltrim(str_replace('Bearer', '', $token));
    }

    public function verifyToken($token, $flag = null)
    {
        $data = explode('.', $token);
        if (count($data) !== 3) {
            throw new JWTException('JWT token格式错误');
        }
        $header = Utils::decoded($data[0]);
        $payload = Utils::decoded($data[1]);
        $this->header->setItem((array) $header);
        $this->payload->setItem((array) $payload);
        // 验证signature是否正确
        $signature = $this->generateSign($data[0], $data[1]);
        if (! hash_equals($signature, $data[2])) {
            throw new JWTException('JWT 验证签名失败');
        }
        // 验证该token是否过期
        if ($this->payload->getNbf() >= time() || time() >= $this->payload->getExp())
        {
            throw new JWTException('JWT token已失效');
        }
        // 检测token是否加入黑名单
        if (! is_null($this->getBlacklist($token)))
        {
            throw new JWTException('JWT token已加入黑名单');
        }
        // 检查是不是单点登录
        if (! is_null($flag)) {
            $splToken = Cache::get($this->singlePointLoginPrefix . $flag);  // 获取到单点登录时存储的token值
            // 如果单点登录的token值和请求中的token不一致时，抛出错误
            if (! hash_equals($splToken, md5($token))) {
                throw new JWTException('JWT 该token值已被占用');
            }
        }
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function getClaims()
    {
        return $this->payload->getClaims();
    }

    public function generateSign($header, $payload, $join = '.')
    {
        $algo = $this->header->getAlg();  // 获取头部里面的algo
        if (! array_key_exists(strtoupper($algo), $this->hmacMapping)) {
            throw new JWTException("JWT algo {$algo} 不是被允许的加密类型");
        }
        $algo = $this->hmacMapping[$algo];
        if (is_object($header)) {
            $header = $header->toArray();
        }
        if (is_array($header)) {
            $header = Utils::encoded((array) $header);
        }
        if (is_object($payload)) {
            $payload = $payload->toArray();
        }
        if (is_array($payload)) {
            $payload = Utils::encoded((array) $payload);
        }
        $string = $header . $join . $payload;
        return hash_hmac($algo, $string, config('jwt.secret'));
    }

    public function singlePointLogin($flag, string $token)
    {
        $key = $this->singlePointLoginPrefix . $flag;
        // 进行单点登录的时候需要把当前正在使用的token存储到缓存里面
        Cache::put($key, md5($token), $this->singlePointLoginTTL);
    }

}
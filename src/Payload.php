<?php

namespace MisMusic\JWTAuth;

use MisMusic\JWTAuth\Contracts\ArrayableContract;
use MisMusic\JWTAuth\Contracts\PayloadContract;

class Payload implements PayloadContract, ArrayableContract
{

    protected $claims;

    protected $iss = 'admin';  // 发行方
    protected $sub;  // 主题
    protected $aud = 'user';  // 受众群体
    protected $exp;  // 过期时间
    protected $nbf;  // 不早于
    protected $iat;  // 发行时间
    protected $jti;  // JWT ID

    public function __construct(Claims $claims)
    {
        $this->claims = $claims;
    }

    public function setItem(array $data)
    {
        foreach ($data as $k => $v) {
            if (property_exists($this, $k)) {
                if ($k === 'claims') {
                    $this->claims->setClaims((array) $v);
                } else {
                    $this->$k = $v;
                }
            }
        }
    }

    public function setClaims(array $claims)
    {
        $this->claims->setClaims($claims);
    }

    public function getClaims()
    {
        return $this->claims;
    }

    public function setIss($iss)
    {
        $this->iss = $iss;
    }

    public function getIss()
    {
        return $this->iss;
    }

    public function setSub($sub)
    {
        $this->sub = $sub;
    }

    public function getSub()
    {
        return $this->sub;
    }

    public function setAud($aud)
    {
        $this->aud = $aud;
    }

    public function getAud()
    {
        return $this->aud;
    }

    public function setExp($exp)
    {
        // TODO: Implement setExp() method.
        $this->exp = $exp;
    }

    public function getExp()
    {
        // TODO: Implement getExp() method.
        return $this->exp;
    }

    public function setNbf($nbf)
    {
        // TODO: Implement setNbf() method.
        $this->nbf = $nbf;
    }

    public function getNbf()
    {
        // TODO: Implement getNbf() method.
        return $this->nbf;
    }

    public function setIat($iat)
    {
        // TODO: Implement getIat() method.
        $this->iat = $iat;
    }

    public function getIat()
    {
        // TODO: Implement getIat() method.
        return $this->iat;
    }

    public function setJti($jti)
    {
        // TODO: Implement setJti() method.
        $this->jti = $jti;
    }

    public function getJti()
    {
        // TODO: Implement getJti() method.
        return $this->jti;
    }

    public function toArray()
    {
        $data = get_object_vars($this);
        $data['claims'] = $data['claims']->getClaims();
        return $data;
    }

}
<?php

namespace MisMusic\JWTAuth;

use MisMusic\JWTAuth\Contracts\ArrayableContract;
use MisMusic\JWTAuth\Contracts\PayloadContract;

class Header implements ArrayableContract
{

    protected $alg = 'HS256';
    protected $typ = 'JWT';

    public function setItem(array $data)
    {
        foreach ($data as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    public function setAlg($algo)
    {
        $this->alg = $algo;
    }

    public function getAlg()
    {
        return $this->alg;
    }

    public function setTyp($type)
    {
        $this->typ = $type;
    }

    public function getTye()
    {
        return $this->typ;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

}
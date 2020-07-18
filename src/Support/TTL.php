<?php

namespace MisMusic\JWTAuth\Support;

trait TTL
{

    protected $ttl;

    public function getTTL()
    {
        if (is_null($this->ttl)) {
            return config('jwt.ttl');
        }
    }

    public function setTTL(int $ttl)
    {
        $this->ttl = $ttl;
    }

}
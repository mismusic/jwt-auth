<?php

namespace MisMusic\JWTAuth\Support;

use Illuminate\Support\Facades\Cache;

trait Blacklist
{

    public $prefix = 'jwt_blacklist_';

    public function add($token, $ttl = null, $prefix = null) :bool
    {
        if (is_null($ttl)) {
            $ttl = config('jwt.blacklist_ttl');
        }
        if (is_null($prefix)) {
            $prefix = $this->prefix;
        }
        $key = $prefix . md5($token, true);
        Cache::add($key, $token, $ttl);
    }

    public function get($token, $prefix = null)
    {
        if (is_null($prefix)) {
            $prefix = $this->prefix;
        }
        $key = $prefix . md5($token, true);
        return Cache::get($key);
    }

}
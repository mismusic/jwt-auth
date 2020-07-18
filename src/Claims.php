<?php

namespace MisMusic\JWTAuth;

use MisMusic\JWTAuth\Contracts\ArrayableContract;

class Claims implements \ArrayAccess, ArrayableContract
{

    private $claims = [];

    public function setClaims(array $data)
    {
        $this->claims = array_merge($this->claims, $data);
    }

    public function getClaims()
    {
        return $this->toArray();
    }

    public function offsetExists($offset)
    {
        return isset($this->claims[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->claims[$offset];
    }

    public function offsetSet($offset, $value)
    {
        return $this->claims[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->claims[$offset]);
    }

    public function toArray()
    {
        return $this->claims;
    }


}
<?php

namespace MisMusic\JWTAuth\Contracts;

interface JWTManage
{

    public function valid($token);

    public function create(array $claims = [], $flag = null);

    public function refresh($token, $flag = null);

    public function delete($token);

}
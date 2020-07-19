<?php

namespace MisMusic\JWTAuth\Contracts;

interface JWTManage
{

    public function getHeader();

    public function getPayload();

    public function getClaims();

    public function getRequestToken();

    public function generateSign($header, $payload, $join = '.');

    public function verifyToken($token, $flag = null);

    public function singlePointLogin($flag, string $token);

    public function getTokenInfo();

    public function valid($token);

    public function create(array $claims = [], $flag = null);

    public function refresh($token, $flag = null);

    public function delete($token);

}
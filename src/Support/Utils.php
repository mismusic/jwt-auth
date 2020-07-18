<?php

namespace MisMusic\JWTAuth\Support;

class Utils
{

    public static function encoded($data)
    {
        if (is_array($data)) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        return self::base64UrlEncode($data);
    }

    public static function decoded($data)
    {
        $data = self::base64UrlDecode($data);
        $result = @json_decode($data, true);
        if ($result === null) {
            return $data;
        } else {
            return $result;
        }
    }

    public static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function base64UrlDecode($data)
    {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (strlen($data) + 3) % 4));
    }


}
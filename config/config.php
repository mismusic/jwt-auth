<?php

return [

    'secret' => env('JWT_SECRET'),  // JWT 密钥

    'ttl' => env('JWT_TTL', 120),  // 生成token的默认过期时间，单位为分钟

    'algo' => env('JWT_ALGO', 'HS256'),  // 生成签名的加密algos

    'blacklist_ttl' => env('JWT_BLACKLIST_TTL', 2592000),  // 加入黑名单的有效时间

];
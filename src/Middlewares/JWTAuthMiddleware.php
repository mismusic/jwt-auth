<?php

namespace MisMusic\JWTAuth\Middlewares;

use Closure;
use MisMusic\JWTAuth\JWTAuth;

abstract class JWTAuthMiddleware extends JWTAuth
{

    abstract public function handle($request, Closure $next);

}
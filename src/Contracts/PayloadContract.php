<?php

namespace MisMusic\JWTAuth\Contracts;

interface PayloadContract
{

    public function setIss($iss);

    public function getIss();

    public function setSub($sub);

    public function getSub();

    public function setAud($aud);

    public function getAud();

    public function setExp($exp);

    public function getExp();

    public function setNbf($nbf);

    public function getNbf();

    public function setIat($iat);

    public function getIat();

    public function setJti($jti);

    public function getJti();

}
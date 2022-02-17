<?php
namespace App\Utils;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;


class JwtUtils
{
    public static function CheckJwt($jwt){
    try 
    {
    $decoded = JWT::decode($jwt, new Key(getenv('JWT_SECRET'), 'HS256'));
    if(UserUtils::CheckSession($decoded->payload)){       
        return true;
    }
    }
    catch(Exception $e)
    {
    return false;
    }

}

}
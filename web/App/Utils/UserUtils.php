<?php
namespace App\Utils;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class UserUtils
{
    public static function CheckSession($payload)
    {
 
    $conn = pg_connect(HOSTSTRING) or die("Deu erro de comunicação com o banco");
    $result = pg_query($conn,"SELECT token FROM usuario_token WHERE discord_id='".$payload->discord_id."'");
    $row_token = pg_fetch_assoc($result);
    if($row_token > 0){
        $token = $row_token['token'];
        return $payload->session_id == $token;
    }
    return false;
    }
    

}
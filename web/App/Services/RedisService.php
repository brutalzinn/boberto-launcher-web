<?php
namespace App\Services;
use Predis;
class RedisService
    {
       
       public static $client = null;


       public static function Init($client){

       if(self::$client == null){     
        Predis\Autoloader::register();  
        $client = new Predis\Client([
            'scheme' => 'tcp',
            'host'   => getenv("BOBERTO_HOST"),
            'port'   => getenv('REDIS_PORT'),
            'password' => getenv("REDIS_PASSWORD")
        ]);
        self::$client = $client;
        }

    }
    }
<?php
namespace App\Controllers;

use App\Services\RedisService;

class RedisController extends BaseController
    {

        public function delRedis(){
            $content = file_get_contents('php://input');
            $decode = json_decode( $content, true );
            if(RedisService::$client->del($decode['id'])){
                return "Redis deleted with success.";
            }else{
                return "Redis cache cant be found.";
            }
        }

        public function clearRedis(){

            $modpacks_file = $this->readJson($this->_modpacks_file);
            foreach ( $modpacks_file as $key => $value)
            {
              RedisService::$client->del($value['id']);
            }
            return "All redis cache are deleted.";
        }
    }
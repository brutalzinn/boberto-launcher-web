<?php
namespace App\Controllers;

use Exception;
use App\Services\RedisService;
use App\Services\FileInfoService;
class ModPackManagerController extends BaseController
    {

        public static function syncModPack(){

            $content = file_get_contents('php://input');
            $decode = json_decode( $content, true );
            $modpacks_new = array();
            $modpack_old = self::dirList(self::$_modpacks_dir);
            foreach ( $decode as $key => $value)
            {
                $modpack_dir = self::pathJoin(self::$_modpacks_dir,$value["directory"]);
                array_push($modpacks_new,$modpack_dir);
            }
            foreach ( $modpack_old as $key => $value)
            {
                if(!in_array($value,$modpacks_new)){     
                   self::recursiveRemove($value);
                }
            }
            if (self::writeJson(self::$_modpacks_file, $decode)){
                return "All modpacks are sync now.";
            }
        }

        public  static function appendModPack(){

            $modpacks_file = self::readJson(self::$_modpacks_file);
            $content = file_get_contents('php://input');
            $decode_content = json_decode( $content, true );
            if(!isset($decode_content['id'])){
                $decode_content['id'] = self::guidv4();
            }
            $modpack_old = array();

            foreach ( $modpacks_file as $value)
            {
              array_push($modpack_old, $value);
            }
            unset($value);

            foreach ($modpack_old as $key => $value){
                if($decode_content['id'] == $value['id']){
                    $modpack_old[$key] = $decode_content;     
                }
                else if($decode_content['id'] != $value['id'] && !self::checkObjectList('id', $modpack_old,$decode_content['id']) ) {
                    array_push($modpack_old, $decode_content);
                }
            }
            unset($value);
            if(count($modpack_old) == 0){
                array_push($modpack_old, $decode_content);
            }

            self::writeJson(self::$_modpacks_file, $modpack_old);
            $id = $decode_content['id'];
            $modpack_file = FileInfoService::GetFileInfo($id);
            $resultado = json_encode($modpack_file,JSON_UNESCAPED_SLASHES );
            RedisService::$client->set($id, $resultado);
            return "Modpack Added. Clear cache for {$id}";
        }

        public static function getModPackFileInfo($id)
        {
            $keyExist = RedisService::$client->exists($id);
            if($keyExist){
                return RedisService::$client->get($id);
             }

            $modpack_file = FileInfoService::GetFileInfo($id);
            $resultado = json_encode($modpack_file, JSON_UNESCAPED_SLASHES);
            RedisService::$client->set($id, $resultado);
            // RedisService::$client->expire($id, 3600);
            return RedisService::$client->get($id);
                  

        }
    }
<?php
    namespace App\Controllers;

    class BaseController{

        public static  $_modpacks_file = "cliente/launcher/config-launcher/modpacks.json";
        public static  $_news_file = "cliente/launcher/news-launcher/news-launcher.json";
        public static  $_launcher_package_file = "cliente/launcher/package.json";
        public static  $_launcher_config_file = "cliente/launcher/config-launcher/config.json";
        public static  $_modpacks_dir = "cliente/files/files/";
        public static  $_launcher_update_dir = "cliente/launcher/update-launcher/";

        public static function  getUrlParams(){
            $url = $_SERVER['REQUEST_URI'];
            $url_components = parse_url($url);
            parse_str($url_components['query'], $params);
            return $params;
        }
        public static function  isRequest($string)
        {
            return  strtolower($string) == strtolower($_SERVER['REQUEST_METHOD']);
        }
        public static function  getParams(){
            $params =  explode('/', $_SERVER['REQUEST_URI']);
            return  array_slice($params, 2); 
        }

        public static function  getFileByUrl($url){
            $params =  explode('/', $url);
            $index = count($params) - 1;
            return  $params[$index];
        }
        
        public static function  reArrayFiles($file)
            {
                $file_ary = array();
                $file_count = count($file['name']);
                $file_key = array_keys($file);
            
                for($i=0;$i<$file_count;$i++)
                {
                    foreach($file_key as $val)
                    {
                        $file_ary[$i][$val] = $file[$val][$i];
                    }
                }
                return $file_ary;
            }

            public static function  dirList($path){
                $dirs = array();
                $structure = glob(rtrim($path, "/").'/*');
                if (is_array($structure)) {
                    foreach($structure as $file) {
                        if (is_dir($file)){
                        array_push($dirs,$file);
                        }
                      
                    }
                }
                return $dirs;
                
            }
            public static function  fileList($path,$filter = ['zip']){
                $cdir = scandir($path);
                $files = array();
                foreach ($cdir as $key => $value){
                    $file_type = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                    if(in_array($file_type,$filter)){
                    array_push($files, $value);
                    }
                }
                return $files;
            }

            public static function  pathJoin($dir,$filename){
               return  $dir . basename($filename);
            }

//thanks to https://stackoverflow.com/questions/11613840/remove-all-files-folders-and-their-subfolders-with-php
           public static function  recursiveRemove($dir) {
                $structure = glob(rtrim($dir, "/").'/*');
                if (is_array($structure)) {
                    foreach($structure as $file) {
                        if (is_dir($file)) self::recursiveRemove($file);
                        elseif (is_file($file)) unlink($file);
                    }
                }
                rmdir($dir);
            }
///Find a list by a obj reference. Util to check if a modpack already exists.
///obj,array,expected
///
         public static function  checkObjectList($obj,$array,$expected){
                $result = false;
                foreach($array as $key => $value) {
                    if($array[$key][$obj] == $expected){
                        $result = true;
                    }
                }
                return $result;
            }

            public static function  writeJson($path, $decode)
            {
             return file_put_contents($path, json_encode($decode,JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT));
            }

            public static function  readJson($path)
            {
                $data = file_get_contents($path);
                $json = json_decode($data, true);
                return  $json;
            }
    }
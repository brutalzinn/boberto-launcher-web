<?php
    namespace App\Controllers;

    class BaseController{

        public $_modpacks_file = "cliente/launcher/config-launcher/modpacks.json";
        public $_launcher_package_file = "cliente/launcher/package.json";
        public $_launcher_config_file = "cliente/launcher/config-launcher/config.json";
        public $_modpacks_dir = "cliente/files/files/";
        public $_launcher_update_dir = "cliente/launcher/update-launcher/";

        public function isRequest($string)
        {
            return  strtolower($string) ==  strtolower($_SERVER['REQUEST_METHOD']);
        }
        public function getParams(){
            $params =  explode('/', $_SERVER['REQUEST_URI']);
            return  array_slice($params, 2); 
        }

        public function getFileByUrl($url){
            $params =  explode('/', $url);
            $index = count($params) - 1;
            return  $params[$index];
        }
        
        public function reArrayFiles($file)
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

            public function dirList($path){
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
            public function fileList($path,$filter = ['zip']){
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

            public function pathJoin($dir,$filename){
               return  $dir . basename($filename);
            }

//thanks to https://stackoverflow.com/questions/11613840/remove-all-files-folders-and-their-subfolders-with-php
           public function recursiveRemove($dir) {
                $structure = glob(rtrim($dir, "/").'/*');
                if (is_array($structure)) {
                    foreach($structure as $file) {
                        if (is_dir($file)) $this->recursiveRemove($file);
                        elseif (is_file($file)) unlink($file);
                    }
                }
                rmdir($dir);
            }

            public function writeJson($path, $decode)
            {
             return file_put_contents($path, json_encode($decode,JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT));
            }
    }
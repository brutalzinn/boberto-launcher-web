<?php
namespace App\Controllers;

use Exception;

class ModPackManagerController extends BaseController
    {

        public function syncModPack(){

            $content = file_get_contents('php://input');
            $decode = json_decode( $content, true );
            $modpacks_new = array();
            $modpack_old = $this->dirList($this->_modpacks_dir, []);
            foreach ( $decode as $key => $value)
            {
                $modpack_dir = $this->pathJoin($this->_modpacks_dir,$value["directory"]);
                array_push($modpacks_new,$modpack_dir);
            }

            foreach ( $modpack_old as $key => $value)
            {
                if(!in_array($value,$modpacks_new)){
                   # $this->recursiveRemove($value);
                }
            }
            var_dump($modpack_old);
            if ($this->writeJson($this->_modpacks_file, $decode)){
                return "All modpacks are sync now.";
            }


        }
    }
<?php
namespace App\Controllers;

use Exception;

class ModPackManagerController extends BaseController
    {

        public function syncModPack(){

            $content = file_get_contents('php://input');
            $decode = json_decode( $content, true );
            $modpacks_new = array();
            $modpack_old = $this->dirList($this->_modpacks_dir);
            foreach ( $decode as $key => $value)
            {
                $modpack_dir = $this->pathJoin($this->_modpacks_dir,$value["directory"]);
                array_push($modpacks_new,$modpack_dir);
            }
            foreach ( $modpack_old as $key => $value)
            {
                if(!in_array($value,$modpacks_new)){     
                   $this->recursiveRemove($value);
                }
            }
            if ($this->writeJson($this->_modpacks_file, $decode)){
                return "All modpacks are sync now.";
            }
        }

        public function appendModPack(){

            $modpacks_file = $this->readJson($this->_modpacks_file);
            $content = file_get_contents('php://input');
            $decode_content = json_decode( $content, true );

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
                else if($decode_content['id'] != $value['id'] && !$this->checkObjectList('id', $modpack_old,$decode_content['id']) ) {
                    array_push($modpack_old, $decode_content);
                }
            }
            unset($value);
            if(count($modpack_old) == 0){
                array_push($modpack_old, $decode_content);
            }

            $this->writeJson($this->_modpacks_file, $modpack_old);
            return "Modpack Added";
        }
    }
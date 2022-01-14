<?php
    namespace App\Controllers;

use Exception;

class LauncherController extends BaseController
    {
        private $_modpacks = "cliente/launcher/config-launcher/modpacks.json";
       
        public function list_modpacks() 
        {
            if(!$this->isRequest("POST")){
                throw new Exception("ONLY POST IS ACCEPTED");
            }

            $data = file_get_contents($this->_modpacks);
            $json = json_decode($data, true);
            return  $json;
        }

    }
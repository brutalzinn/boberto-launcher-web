<?php
    namespace App\Controllers;

use Exception;

class LauncherController extends BaseController
    {
        private $_modpacks = "cliente/launcher/config-launcher/modpacks.json";
        private $_config = "cliente/launcher/config-launcher/config.json";


        public function list_modpacks() 
        {
            if(!$this->isRequest("GET")){
                throw new Exception("ONLY GET IS ACCEPTED");
            }

            $data = file_get_contents($this->_modpacks);
            $json = json_decode($data, true);
            return  $json;
        }

        public function launcher_Config() 
        {
            if(!$this->isRequest("POST")){
                throw new Exception("ONLY POST IS ACCEPTED");
            }

            $data = file_get_contents('php://input');
            $fp = fopen($this->_config, 'w');
            fwrite($fp, $data);
            fclose($fp);
    
        }

    }
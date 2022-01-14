<?php
    namespace App\Controllers;

use Exception;
use ZipArchive;

class LauncherController extends BaseController
    {
        private $_modpacks = "cliente/launcher/config-launcher/modpacks.json";
        private $_config = "cliente/launcher/config-launcher/config.json";
        private $_modpacks_dir = "cliente/files/files/";

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
        public function uploadFile()
        {
        $target_file =  $this->_modpacks_dir . basename($_FILES["file"]["name"]);
        $file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if($file_type != "zip"){
            throw new Exception("ONLY ZIP FILE IS SUPPORTED.");
        }
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $zip = new ZipArchive;
  
            if ($zip->open($target_file) === TRUE) {
                $zip->extractTo($this->_modpacks_dir . basename($_POST["directory"]));
                $zip->close();
                unlink($target_file);
            } else {
                unlink($target_file);
                return "Extract process exited with error... removing uploaded files.";

            }
            return "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
          } else {
            return "Sorry, there was an error uploading your file.";
          }

        }

    }
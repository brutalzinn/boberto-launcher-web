<?php
    namespace App\Controllers;

use Exception;
use ZipArchive;

class LauncherController extends BaseController
    {
        private $_modpacks = "cliente/launcher/config-launcher/modpacks.json";
        private $_launcher_package = "cliente/launcher/package.json";

        private $_config = "cliente/launcher/config-launcher/config.json";
        private $_modpacks_dir = "cliente/files/files/";
        private $_launcher_update_dir = "cliente/launcher/update-launcher/";

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

        public function uploadLauncherZips()
        {
            
            $file_ary = $this->reArrayFiles($_FILES['file']);         
            foreach ($file_ary as $file) {
                $target_file =  $this->_launcher_update_dir . basename($file["name"]);
                $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                if($file_type != "zip"){
                    throw new Exception("ONLY ZIP FILE IS SUPPORTED.");
                }
                if (!move_uploaded_file($file["tmp_name"], $target_file)) {
                    return "cant move file" . $file['name'] . 'to launcher update folder';
                    }
            }         
            return "All new launcher are released.";
        }

        public function updateLauncherVersion(){
            $cdir = scandir($this->_launcher_update_dir);
            $old_files = array();
            foreach ($cdir as $key => $value){
                $file_type = strtolower(pathinfo($value, PATHINFO_EXTENSION));
                if($file_type == "zip"){
                array_push($old_files, $value);
                }
            }
            
            $content = file_get_contents('php://input');

            $launcher_package = file_get_contents($this->_launcher_package);
            $backup_package = json_decode($launcher_package, true);

            $decode = json_decode( $content, true );
            foreach ( $decode["packages"] as $valor){
                var_dump($valor);

                if($valor == null){
                    $decode[$valor] = $backup_package[$valor];
                }else{
                    $filename = $this->getFileByUrl($valor["url"]);
                    $teste = in_array($filename,$old_files);

                    if(!$teste){
                        $antigo = $this->getFileByUrl($backup_package[$valor]["url"]);
                        $file_old = $this->_launcher_update_dir . basename($antigo);
                        if(file_exists($file_old)){
                            unlink($file_old);
                        }
                    }
                }
            }
            $fp = fopen($this->_launcher_package, 'w');
            fwrite($fp, $decode);
            fclose($fp);
        }

    }
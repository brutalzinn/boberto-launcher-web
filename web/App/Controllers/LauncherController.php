<?php
namespace App\Controllers;

use Exception;
use ZipArchive;

class LauncherController extends BaseController
    {

        public function list_modpacks() 
        {
            if(!$this->isRequest("GET")){
                throw new Exception("ONLY GET IS ACCEPTED");
            }

            $data = file_get_contents($this->_modpacks_file);
            $json = json_decode($data, true);
            return  $json;
        }

        public function launcher_Config() 
        {
            if(!$this->isRequest("POST")){
                throw new Exception("ONLY POST IS ACCEPTED");
            }

            $data = file_get_contents('php://input');
            $fp = fopen($this->_launcher_config_file, 'w');
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

        public function updateLauncherVersion()
        {

            $launcher_package = file_get_contents($this->_launcher_package_file);
            $backup_package = json_decode($launcher_package, true);
            if($this->isRequest("GET")){
                return $backup_package;
            }
            $old_files = $this->fileList($this->_launcher_update_dir);
            $content = file_get_contents('php://input');
          
            $decode = json_decode( $content, true );
            foreach ( $decode["packages"] as $key => $value){
                
                if($decode["packages"][$key] == null){
                    $decode["packages"][$key] = $backup_package["packages"][$key];
                }else{
                    $filename = $this->getFileByUrl($value["url"]);
                    $teste = in_array($filename,$old_files);

                    if(!$teste){
                        $antigo = $this->getFileByUrl($backup_package["packages"][$key]["url"]);
                        $file_old = $this->_launcher_update_dir . basename($antigo);
                        if(file_exists($file_old)){
                            unlink($file_old);
                        }
                    }
                }
            }
            if($this->writeJson($this->_launcher_package_file, $decode))
            {
                return "Launcher config updated.";
            }
        }

    }
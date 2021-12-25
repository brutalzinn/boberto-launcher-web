<?php
function dirToArray($dir) {
   $nomemodpack = str_replace("files/", "", $dir);
   $cdir = scandir($dir);
   foreach ($cdir as $key => $value){
      if (!in_array($value,array(".",".."))){
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
            dirToArray($dir . DIRECTORY_SEPARATOR . $value);
         } else {
            $hash = hash_file('sha1', $dir . "/" . $value);
            $size = filesize($dir . "/" . $value);
            $path = str_replace($dir."/", "", $nomemodpack . "/" . $value);
            $url = "http://" . $_SERVER['HTTP_HOST'] ."/cliente/files/". $dir . "/" . $value;
            if (strpos($path, "libraries") !== false) {
               $type = "LIBRARY";
            } else if (strpos($path, "mods") !== false) {
               $type = "MOD";
            } else if (strpos($path, "versions") !== false) {
               $type = "VERIONSCUSTOM";
            } else {
               $type = "FILE";
            }
            echo "{\"path\":\"$path\",\"size\":$size,\"sha1\":\"$hash\",\"url\":\"$url\",\"type\":\"$type\"},";
         }
      }
   }
}

function getDirectory($identificador){
$json_file = file_get_contents('../launcher/config-launcher/modpacks.json');
$json = json_decode($json_file); // decode the JSON into an associative array
foreach ( $json as $e )
    {
       if($e->id == $identificador){
          return "files/".$e->directory;
       }
    }
}

header("Content-Type: application/json; charset=UTF-8");
if (isset($_GET['modpack'])) {
echo "[", dirToArray(getDirectory($_GET['modpack'])), "\"\"]";
}
?>
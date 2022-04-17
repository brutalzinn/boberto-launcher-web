<?php
namespace App\Services;

use App\Utils\PathUtils;
use Predis;

class FileInfoService extends PathUtils
{
private static $DIRS = array();
private static function dirToArray($dir,$modpack) 
{
    $cdir = scandir($dir);
    foreach ($cdir as $key => $value){
        if (!in_array($value,array(".",".."))){
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                self::dirToArray($dir . DIRECTORY_SEPARATOR . $value, $modpack);
            } else {

                $hash = hash_file('sha1', $dir . "/" . $value);
                $size = filesize($dir . "/" . $value);
                $path = str_replace("files/".$modpack."/", "", $dir . "/" . $value);

                $url = "http://" . $_SERVER['HTTP_HOST'] ."/". $dir . "/" . $value;
                if (strpos($path, "libraries") !== false) {
                $type = "LIBRARY";
                } else if (strpos($path, "mods") !== false) {
                $type = "MOD";
                } else if (strpos($path, "versions") !== false) {
                $type = "VERIONSCUSTOM";
                } else {
                $type = "FILE";
                }
                $obj = array("path"=>$path, "size"=>$size, "sha1"=>$hash,"url"=>$url,"type"=>$type);
                array_push(self::$DIRS, $obj);
                }
            }
        }
}
public static function getDirectory($identificador){
    $json_file = file_get_contents(self::$_modpacks_file);
    $json = json_decode($json_file); // decode the JSON into an associative array
    foreach ( $json as $e )
        {
           if($e->id == $identificador){
              return $e->directory;
           }
        }
        return null;
}

public static function GetFileInfo($id){
    $modpack = self::getDirectory($id);
    $dir = self::$_modpacks_dir . $modpack;
    
    if(!is_dir($dir)){
       return [];
    }
    
    self::dirToArray($dir, $modpack);
    return self::$DIRS;
}


}
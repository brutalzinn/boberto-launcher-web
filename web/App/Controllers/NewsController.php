<?php
namespace App\Controllers;

use Exception;

class NewsController extends BaseController
{

public static function  readNews(){
    $news_file = self::readJson(self::$_news_file);

  //  print_r($this->getUrlParams());
    return $news_file;
}

public static function  updateNews()
{

    $news_file = self::readJson(self::$_news_file);

    $content = file_get_contents('php://input');
    $decode_content = json_decode( $content, true );
    if(!isset($decode_content['id'])){
        $decode_content['id'] = uniqid();
    }

    $modpack_old = array();

    foreach ($news_file["news"] as $key => $value)
    {
      array_push($modpack_old, $value);
    }

    unset($value);

    foreach ($modpack_old as $key => $value){
        if($decode_content['id'] == $value['id'])
        {
            $modpack_old[$key] = $decode_content;     
        }
        else if($decode_content['id'] != $value['id'] && !self::checkObjectList('id', $modpack_old, $decode_content['id'])) 
        {
            array_push($modpack_old, $decode_content);
        }
    }
    unset($value);
    if(count($modpack_old) == 0){
        array_push($modpack_old, $decode_content);
    }
    $jsonObject = array('news' => $modpack_old);
    
    self::writeJson(self::$_news_file, $jsonObject);
    return "News Added";

}

public static function  deleteNews()
{

    $news_file = self::readJson(self::$_news_file);
    $content = file_get_contents('php://input');
    $decode_content = json_decode( $content, true );
  
    $modpack_old = array();

    foreach ($news_file["news"] as $key => $value)
    {
      array_push($modpack_old, $value);
    }

    unset($value);

    foreach ($modpack_old as $key => $value){
        if($decode_content['id'] == $value['id'])
        {
            unset($modpack_old[$key]);     
        }
    }

    unset($value);
    $arr2 = array_values($modpack_old);

    $jsonObject = array('news' => $arr2);
    
    self::writeJson(self::$_news_file, $jsonObject);
    return "News deleted";

}

}
<?php
namespace App\Controllers;

class NewsController extends BaseController
{

public static function  readNews($page, $limit){
    $news_file = self::readJson(self::$_news_file);
    $file_olds = array();
    foreach ($news_file as $key => $value){
        array_push($file_olds,$value);
    }
    return $file_olds;
}
//wrong way to do this.
public static function  AddOrUpdateNews()
{

    $news_file = self::readJson(self::$_news_file);

    $content = file_get_contents('php://input');
    $decode_content = json_decode( $content, true );
    if(!isset($decode_content['id'])){
        $decode_content['id'] = self::guidv4();
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
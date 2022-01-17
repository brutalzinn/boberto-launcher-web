<?php
namespace App\Controllers;

use Exception;

class NewsController extends BaseController
{

public function readNews(){
    $news_file = $this->readJson($this->_news_file);
    if(!$this->isRequest("GET")){
        return "This routes only accepts get request.";
    }
  //  print_r($this->getUrlParams());
    return $news_file;
}

public function updateNews()
{

    $news_file = $this->readJson($this->_news_file);
    if(!$this->isRequest("POST")){
        return "This routes only acccepts post.";
    }

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
        else if($decode_content['id'] != $value['id'] && !$this->checkObjectList('id', $modpack_old, $decode_content['id'])) 
        {
            array_push($modpack_old, $decode_content);
        }
    }
    unset($value);
    if(count($modpack_old) == 0){
        array_push($modpack_old, $decode_content);
    }
    $jsonObject = array('news' => $modpack_old);
    
    $this->writeJson($this->_news_file, $jsonObject);
    return "News Added";

}

public function deleteNews()
{

    $news_file = $this->readJson($this->_news_file);
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
    
    $this->writeJson($this->_news_file, $jsonObject);
    return "News deleted";

}

}
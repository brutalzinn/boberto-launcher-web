<?php 

class Route{

private $_uri = array();
private $_controller = array();
private $_controller_method = array();
private $_params = array();


public function add($uri, $controller = null, $method = null, $param = false){

    $this->_uri[] = '/' . trim($uri, '/');
    $this->_params[] = $param;


    if($controller != null){
        $this->_controller[] = $controller;
    }
    if($method != null){
        $this->_controller_method[] = $method;
    }
   
    
}
private function Execute($key){
    $service = 'App\Controllers\\'.ucfirst($this->_controller[$key]).'Controller';
    if(class_exists($service) == false){

        throw new Exception("Controller not found: ". $this->_controller[$key]);

    }
    if(!method_exists($service, $this->_controller_method[$key]) && !is_callable(array($service, $this->_controller_method[$key])))
    {

        throw new Exception("Route not found on controller: ". $this->_controller[$key]);

    }
    return call_user_func(array(new $service(), $this->_controller_method[$key]));
}
public function submit(){

    $uriGetParm = $_SERVER['REQUEST_URI'];
    $params =  explode('/', $_SERVER['REQUEST_URI']);
    $params =  array_slice($params, 1); 
    preg_match('#^\/[^\/]*#',$uriGetParm, $custom_url);

    foreach($this->_uri as $key => $value)
    {
        if(preg_match("#^$value$#",$uriGetParm, $normal_url)){
            return $this->Execute($key);
        }else if(count($params) > 0 && $this->_params[$key]) {
            if($custom_url[0] == $value){
            return $this->Execute($key);
            }
        }
    }
    throw new Exception("Controller not found for this route");

}




}
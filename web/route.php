<?php 

class Route{

private $_uri = array();
private $_controller = array();
private $_controller_method = array();
private $_params = array();


public function add($uri, $controller = null, $method = null){

    $this->_uri[] = '/' . trim($uri, '/');

    if($controller != null){
        $this->_controller[] = $controller;
    }
    if($method != null){
        $this->_controller_method[] = $method;
    }
}

public function submit(){

    $uriGetParm = $_SERVER['REQUEST_URI'];
    echo $uriGetParm;
    foreach($this->_uri as $key => $value)
    {
        
        if(preg_match("#^$value$#",$uriGetParm))
        {

            echo "encontrou " . $value;
            if(is_string($this->_controller[$key]))
            {
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

        }
    }
   // throw new Exception("Controller not found for this route");

}




}
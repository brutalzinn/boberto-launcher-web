<?php
    namespace App\Controllers;

    class BaseController{

        public function isRequest($string)
        {
            return  strtolower($string) ==  strtolower($_SERVER['REQUEST_METHOD']);
        }
        public function getParams(){
            $params =  explode('/', $_SERVER['REQUEST_URI']);
            return  array_slice($params, 2); 
        }

    }
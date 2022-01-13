<?php
    namespace App\Controllers;

    class BaseController{

        public function REQUEST($string)
        {
            return  strtolower($string) ==  strtolower($_SERVER['REQUEST_METHOD']);
        }
        public function params(){
            return explode('/', $_SERVER['REQUEST_URI']);
        }

    }
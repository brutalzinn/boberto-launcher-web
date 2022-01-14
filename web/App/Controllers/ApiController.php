<?php
    namespace App\Controllers;

    class ApiController extends BaseController
    {

        public function index() 
        {
           return  "sucesso index";
        }
        public function teste() 
        {
           return  "sucesso teste ". $this->getParams()[0];
        }
    
    }
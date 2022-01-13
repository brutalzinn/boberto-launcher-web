<?php
    namespace App\Controllers;

    class LauncherController
    {
       
        public function index() 
        {
           return  "url funcionando " . $_SERVER['REQUEST_METHOD'];
        }

    }
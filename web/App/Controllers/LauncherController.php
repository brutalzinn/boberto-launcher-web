<?php
    namespace App\Controllers;

    class LauncherController
    {
        private $method;
        private $uri;
       
        function __construct($method,$uri) {
            $this->method = $method;
            $this->uri = $uri;

        }
        public function modpacks() 
        {
           return  "sucesso " . $this->method;
        }

        public function post() 
        {
            $data = $_POST;

           // return User::insert($data);
        }

        public function update() 
        {
            
        }

        public function delete() 
        {
            
        }
    }
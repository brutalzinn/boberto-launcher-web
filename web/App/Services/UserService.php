<?php
    namespace App\Services;

    class UserService
    {
        public function teste() 
        {
           return  "sucesso";
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
<?php

namespace Service;

use Model\User;

require_once('./App/models/User.php');

class LoginService{

    // private $data = [];
    private $user;

    public function __construct(){
        // $this->data = $data;
        
        $this->user = new User();

    }

   public function apiLogin($request){
        $email = $request['email'];
        $cpf = $request['cpf'];

        $user = $this->user->getUser($email, $cpf);

        return $user;

   }


    

}
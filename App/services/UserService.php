<?php

namespace Service;

use Model\User;

require_once('./App/models/User.php');

class UserService{

    private $user;

    public function __construct(){
        
        $this->user = new User();

    }

    public function apiGet($param){


       if(empty($param)){
            $users = $this->user->getAll();
       }else{
            $users = $this->user->getById($param);
       }

       if($users){
            return $users;
       }else{
            $error = [
                'error' => 'not_found',
                'message' => 'Usuario nao encontrado!'
            ];
            http_response_code(404); 
            return ($error);
       }

    }

    public function apiPost($request){
      

        $name = $request['name'];
        $email = $request['email'];
        $cpf = $request['cpf'];

        $check_cpf = $this->cpfExists($cpf);
        $check_email = $this->emailExists($email);

        if($check_cpf || $check_email){
            $error = [
                'error' => 'denied',
                'message' => 'Email e/ou CPF ja existem no sistema!'
            ];
            http_response_code(409); 
            return ($error);
        }else{
            $retorno =  $this->user->insert($name, $email, $cpf);
            return $retorno;
        }
      
    }

    public function emailExists($email) {

        $response =  $this->user->checkEmailExists($email);
        return $response ? true : false;
    }

    public function cpfExists($cpf) {

    
        $response =  $this->user->checkCpfExists($cpf);
        return $response ? true : false;
    }

    public function apiPut($id, $request){
        $response = $this->user->update($id, $request);

        return $response;

    }

    public function apiDelete($id){
        
        $response = $this->user->delete($id);

        if($response === null){
            $error = [
                'error' => 'not_found',
                'message' => 'Usuario nao encontrado!'
            ];
            http_response_code(409); 
            return ($error);
        }

        return $response;

       
       
    }



    

}
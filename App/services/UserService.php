<?php

namespace Service;

use Config\Database;
use Model\User;


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
      
        if(isset($request['name']) && isset($request['email'])  && isset($request['cpf']) ){
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
                if($retorno){
                    http_response_code(201);
                    return [
                        'status' => 'success',
                        'message' => 'Usuario cadastrado com sucesso!'
                    ];
                }else{
                    http_response_code(400);
                    return [
                        'status' => 'error',
                        'message' => 'Erro ao cadastrar o usuario'
                    ];
                }
            }
        }
        $error = [
            'error' => 'denied',
            'message' => 'Email e Nome sao obrigatorios!'
        ];
        http_response_code(422); 
        return ($error);
      
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

        $user = $this->apiGet($id);
        if (!array_key_exists('error', $user)){
            $name = $request['name'];
            $email = $request['email'];
            $cpf = $request['cpf'];

            $validateEmail = $this->validateEmail($id, $email);
            $validateCpf = $this->validateCpf($id, $cpf);

            if($validateEmail || $validateCpf){
                $error = [
                    'error' => 'denied',
                    'message' => 'Email e/ou CPF ja existentes para outro usuario!'
                ];
                http_response_code(409); 
                return $error;
            } 

            $user = $this->user->update($id, $name, $email, $cpf,$user);
            if($user){
                http_response_code(201); 
                return [
                    'status' => 'success',
                    'message' => 'Usuario editado com sucesso!'
                ];
            }else{
                http_response_code(400); 
                return [
                    'status' => 'error',
                    'message' => 'Nenhuma linha afetada!'
                ];
            }
        }

        return $user;

    }

    public function validateEmail($id, $email){
        $response = $this->user->verifyEmail($id, $email);
        return $response ? true : false;

    }

    public function validateCpf($id, $cpf){
        $response = $this->user->verifyCpf($id, $cpf);
        return $response ? true : false;
        
    }

    public function apiDelete($id){
        
        $response = $this->user->delete($id);

        if($response === null){
            $error = [
                'error' => 'not_found',
                'message' => 'Usuario nao encontrado!'
            ];
            http_response_code(404); 
            return ($error);
        }

        return $response;

       
       
    }



    

}
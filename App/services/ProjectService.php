<?php

namespace Service;

use Config\Database;
use Model\Project;

class ProjectService{

    private $project;

    public function __construct(){
        
        $this->project = new Project();

    }

    public function apiGet($param){
     
       if(empty($param)){
            $projects = $this->project->getAll();
       }else{
            $projects = $this->project->getById($param);
       }

       if($projects){
            http_response_code(200); 
            return $projects;
        }else{
            $error = [
                'error' => 'not_found',
                'message' => 'Projeto nao encontrado!'
            ];
            http_response_code(404); 
            return ($error);
        }

      
    }

    public function apiPost($request){
      
        $name = $request['name'];
        $final_date = $request['final_date'];

       
        $response =  $this->project->insert($name, $final_date);
        if($response){
            http_response_code(201);
            return [
                'status' => 'success',
                'message' => 'Projeto cadastrado com sucesso!'
            ];
        }else{
            http_response_code(400);
            return [
                'status' => 'error',
                'message' => 'Erro ao cadastrar o projeto'
            ];
        }
       
      
    }


    public function apiPut($id, $request){

        $project = $this->apiGet($id);
        if (!array_key_exists('error', $project)){
            $name = $request['name'];
            $final_date = $request['final_date'];

            $response = $this->project->update($id, $name, $final_date, $project);
            if($response){
                http_response_code(201); 
                return [
                    'status' => 'success',
                    'message' => 'Projeto editado com sucesso!'
                ];
            }else{
                http_response_code(400); 
                return [
                    'status' => 'error',
                    'message' => 'Nenhuma linha afetada!'
                ];
            }
        }
     
        return $project;

       
       
    }

    public function apiDelete($id){
        
        $response = $this->project->delete($id);

        if($response){
            http_response_code(201); 
            return [
                'status' => 'success',
                'message' => 'Projeto deletado com sucesso!'
            ];
        }else{
            http_response_code(400); 
            return [
                'status' => 'error',
                'message' => 'Nenhuma linha afetada!'
            ];
        }

    }

    public function apiClose($id, $request){

        $status = $request['status'];

        $verifyTask = $this->verifyTask($id);

        if($verifyTask){
            $error = [
                'error' => 'denied',
                'message' => 'Existem tarefas em aberto para esse projeto!'
            ];
            http_response_code(409); 
            return ($error);
        }else{
            $response = $this->project->updateStatus($id, $status);
            if($response){
                http_response_code(201);
                return [
                    'status' => 'success',
                    'message' => 'Projeto finalizado com sucesso!'
                ];
            }else{
                http_response_code(400);
                return [
                    'status' => 'error',
                    'message' => 'Projeto nao encontrado!'
                ];
            }
        }

       
    }

    public function verifyTask($id){
        return $this->project->verifyTask($id);

    }



    

}
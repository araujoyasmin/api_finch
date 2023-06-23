<?php

namespace Service;

use Model\Project;

require_once('./App/models/Project.php');

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
        return $response;
       
      
    }


    public function apiPut($id, $request){
        
        $response = $this->project->update($id, $request);

        if($response === null){
            throw new \InvalidArgumentException('Nenhum registro encontrado');
        }

        return $response;

       
       
    }

    public function apiDelete($id){
        
        $response = $this->project->delete($id);

        if($response === null){
            http_response_code(404);
            return [
                'status' => 'not_found',
                'message' => 'Projeto nao encontrado!'
            ];
        }

        return $response;

    }

    public function apiClose($id, $request){

        $status = $request['status'];
        
        $response = $this->project->updateStatus($id, $status);

        if($response === null){
            http_response_code(404);
            return [
                'status' => 'not_found',
                'message' => 'Projeto nao encontrado!'
            ];
        }

        return $response;

       
       
    }



    

}
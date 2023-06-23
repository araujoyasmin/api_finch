<?php

namespace Service;

use Model\Project;

require_once('./App/models/Project.php');

class ProjectService{

    // private $data = [];
    private $project;

    public function __construct(){
        // $this->data = $data;
        
        $this->project = new Project();

    }

    public function apiGet($request){
        // echo 'apiget';exit;
        $param = $request['param'];

        // echo $param;exit;

       if(empty($param)){
            $users = $this->project->getAll();
       }else{
            $users = $this->project->getById($param);
       }

       return $users;

      
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
            throw new \InvalidArgumentException('Nenhum registro encontrado');
        }

        return $response;


       
       
    }

    public function apiClose($id, $request){

        $status = $request['status'];
        
        $response = $this->project->updateStatus($id, $status);

        if($response === null){
            throw new \InvalidArgumentException('Nenhum registro encontrado');
        }

        return $response;

       
       
    }



    

}
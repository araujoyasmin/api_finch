<?php

namespace Service;

use Model\Task;
use Model\Project;

require_once('./App/models/Task.php');
require_once('./App/models/Project.php');

class TaskService{

    private $task;
    private $project;

    public function __construct(){
        
        $this->task = new Task();
        $this->project = new Project();

    }

    public function apiGet($param){

       if(empty($param)){
            $tasks = $this->task->getAll();
       }else{
            $tasks = $this->task->getById($param);
       }

        if($tasks){
            return $tasks;
        }else{
            $error = [
                'error' => 'not_found',
                'message' => 'Tarefa nao encontrada!'
            ];
            http_response_code(404); 
            return ($error);
        }

      
    }

    public function apiPost($request){
      
        $id_project = $request['id_project'];
        $id_user = $request['id_user'];
        $title = $request['title'];
        $description = $request['description'];
        $final_date = $request['final_date'];

        $checkDeadline = $this->checkDeadLine($final_date);

        if($checkDeadline){
            $error = [
                'error' => 'invalid_task_deadline',
                'message' => 'O prazo final da tarefa nao pode ser menor que a data corrente'
            ];
            http_response_code(400); 
            return ($error);
        }

        $checkProject = $this->checkFinalDateProject($id_project, $final_date);

        if ($checkProject) {
            $error = [
                'error' => 'invalid_task_deadline',
                'message' => 'O prazo final da tarefa nao pode ser maior que o prazo final do projeto!'
            ];
            http_response_code(400); 
            return ($error);
        }
    
        $response = $this->task->insert($id_project, $id_user, $title, $description, $final_date);
    
        return [
            'type' => 'success',
            'return' => $response
        ];

    }

    public function checkFinalDateProject($id_project,$final_date){
        $response = $this->project->getFinalDate($id_project);

        $dt_task = new \DateTime($final_date);
        $dt_project = new \DateTime($response);
        return ($dt_task > $dt_project) ? true : false;
    
    }

    public function checkDeadLine($final_date){
        $today = new \DateTime();
        $dateString = $today->format('Y-m-d');

        return ($final_date < $dateString) ? true : false;
    }

    

    public function apiPut($id, $request){
        $id_project = $request['id_project'];
        $final_date = $request['final_date'];
        $checkDeadline = $this->checkDeadLine($final_date);

        if($checkDeadline){
            $error = [
                'error' => 'invalid_task_deadline',
                'message' => 'O prazo final da tarefa nao pode ser menor que a data corrente'
            ];
            http_response_code(400); 
            return ($error);
        }

        $checkProject = $this->checkFinalDateProject($id_project, $final_date);

        if ($checkProject) {
            $error = [
                'error' => 'invalid_task_deadline',
                'message' => 'O prazo final da tarefa nao pode ser maior que o prazo final do projeto!'
            ];
            http_response_code(400); 
            return ($error);
        }
        
        $response = $this->task->update($id, $request);

        if($response === null){
            throw new \InvalidArgumentException('Nenhum registro encontrado');
        }

        return $response;
       
       
    }

    public function apiDelete($id){
        
        $response = $this->task->delete($id);

        if($response === null){
            http_response_code(404);
            return [
                'status' => 'not_found',
                'message' => 'Tarefa nao encontrada!'
            ];
        }

        return $response;

       
       
    }

    public function apiClose($id, $request){

        $status = $request['status'];
        
        $response = $this->task->updateStatus($id, $status);

        if($response === null){
            throw new \InvalidArgumentException('Nenhum registro encontrado');
        }

        return $response;

       
       
    }



    

}
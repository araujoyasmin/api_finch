<?php

use Service\TaskService;

require_once ('./App/services/TaskService.php');

class TaskController {
    private $taskService;

    public function __construct() {
        $this->taskService = new TaskService();
    }

    public function post() {
        try{
            $postjson = json_decode(file_get_contents('php://input'),true);
            return $this->taskService->apiPost($postjson);
        }catch(Exception $e){
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        
    }

    public function get($request = null) {
        try{
            return $this->taskService->apiGet($request);
        }catch(Exception $e){
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        

    }

    public function put($param) {
        try{
            $putJson = json_decode(file_get_contents('php://input'),true);
            return $this->taskService->apiPut($param, $putJson);
        }catch(Exception $e){
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        
       
    }

    public function delete($param) {
        try{
            return $this->taskService->apiDelete($param);
        }catch(Exception $e){
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        
    }

    public function patch($param){
        try{
            $patchJson = json_decode(file_get_contents('php://input'),true);
            return $this->taskService->apiClose($param, $patchJson);
        }catch(Exception $e){
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        
    }
}
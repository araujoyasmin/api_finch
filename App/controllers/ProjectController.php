<?php

use Service\ProjectService;

require_once ('./App/services/ProjectService.php');

class ProjectController {
    private $projectService;

    public function __construct() {
        $this->projectService = new ProjectService();
    }

    public function post() {
        try{
        $postjson = json_decode(file_get_contents('php://input'),true);
        return $this->projectService->apiPost($postjson);

        }catch(Exception $e){
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

    }

    public function get($request = null) {
        try{
            return $this->projectService->apiGet($request);

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
            return $this->projectService->apiPut($param, $putJson);

        }catch(Exception $e){
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete($param) {
        try{
            return $this->projectService->apiDelete($param);
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
           return $this->projectService->apiClose($param, $patchJson);
        }catch(Exception $e){
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        
    }
}
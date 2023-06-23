<?php

use Service\ProjectService;

require_once ('./App/services/ProjectService.php');

class ProjectController {
    private $projectService;

    public function __construct() {
        $this->projectService = new ProjectService();
    }

    public function post() {
        $postjson = json_decode(file_get_contents('php://input'),true);
        $response = $this->projectService->apiPost($postjson);

        return $response;
    }

    public function get($request = null) {
       
        $response = $this->projectService->apiGet($request);


        return $response;
        
    }

    public function put($param) {
        $putJson = json_decode(file_get_contents('php://input'),true);
        $response = $this->projectService->apiPut($param, $putJson);

        return $response;
        
    }

    public function delete($param) {
        $response = $this->projectService->apiDelete($param);
        return $response;
       
    }
    
    public function patch($param){
        $patchJson = json_decode(file_get_contents('php://input'),true);
        $response = $this->projectService->apiClose($param, $patchJson);
        return $response;
    }
}
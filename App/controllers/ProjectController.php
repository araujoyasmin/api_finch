<?php

use Service\ProjectService;

require_once ('./App/services/ProjectService.php');

class ProjectController {
    private $projectService;

    public function __construct(ProjectService $projectService) {
        $this->projectService = $projectService;
    }

    public function create() {
        $postjson = json_decode(file_get_contents('php://input'),true);
        $response = $this->projectService->apiPost($postjson);

        return $response;
    }

    public function read($request) {
        // echo 'read';exit;
        $response = $this->projectService->apiGet($request);


        return $response;
        
    }

    public function update($request) {
        $param = $request['param'];
        $putJson = json_decode(file_get_contents('php://input'),true);
        $response = $this->projectService->apiPut($param, $putJson);

        return $response;
        
    }

    public function delete($request) {
        $param = $request['param'];
        $response = $this->projectService->apiDelete($param);
        return $response;
       
    }
    
    public function close($request){
        $param = $request['param'];
        $patchJson = json_decode(file_get_contents('php://input'),true);
        $response = $this->projectService->apiClose($param, $patchJson);
        return $response;
    }
}
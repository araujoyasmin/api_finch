<?php

use Service\TaskService;

require_once ('./App/services/TaskService.php');

class TaskController {
    private $taskService;

    public function __construct() {
        $this->taskService = new TaskService();
    }

    public function post() {
        $postjson = json_decode(file_get_contents('php://input'),true);
        $response = $this->taskService->apiPost($postjson);

        return $response;
    }

    public function get($request = null) {
      
        $response = $this->taskService->apiGet($request);

        return $response;

    }

    public function put($param) {
        $putJson = json_decode(file_get_contents('php://input'),true);
        $response = $this->taskService->apiPut($param, $putJson);

        return $response;
       
    }

    public function delete($param) {
        $response = $this->taskService->apiDelete($param);
        return $response;
    }

    public function patch($param){
        $patchJson = json_decode(file_get_contents('php://input'),true);
        $response = $this->taskService->apiClose($param, $patchJson);
        return $response;
    }
}
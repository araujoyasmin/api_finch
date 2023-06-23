<?php

use Service\TaskService;

require_once ('./App/services/TaskService.php');

class TaskController {
    private $taskService;

    public function __construct(TaskService $taskService) {
        $this->taskService = $taskService;
    }

    public function create() {
        $postjson = json_decode(file_get_contents('php://input'),true);
        $response = $this->taskService->apiPost($postjson);

        // print_r($response);exit;

        return $response;
    }

    public function read($request) {
      
        $response = $this->taskService->apiGet($request);

        return $response;

    }

    public function update($request) {
        $param = $request['param'];
        $putJson = json_decode(file_get_contents('php://input'),true);
        $response = $this->taskService->apiPut($param, $putJson);

        return $response;
       
    }

    public function delete($request) {
        $param = $request['param'];
        $response = $this->taskService->apiDelete($param);
        return $response;
    }

    public function close($request){
        $param = $request['param'];
        $patchJson = json_decode(file_get_contents('php://input'),true);
        $response = $this->taskService->apiClose($param, $patchJson);
        return $response;
    }
}
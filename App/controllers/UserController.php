<?php

use Service\UserService;

require_once ('./App/services/UserService.php');

class UserController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function post() {
        
        $postjson = json_decode(file_get_contents('php://input'),true);
        
        $response = $this->userService->apiPost($postjson);

        return $response;
    }

    public function get($request = null) {
      
        $response = $this->userService->apiGet($request);

        return $response;

    }

    public function put($param) {

        $putJson = json_decode(file_get_contents('php://input'),true);
        $response = $this->userService->apiPut($param, $putJson);
        return $response;
    }

    public function delete($param) {
        
        $response = $this->userService->apiDelete($param);
        return $response;
    }
}
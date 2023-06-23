<?php

use Service\UserService;

require_once ('./App/services/UserService.php');

class UserController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
        // $this->loginController = new LoginController($this->loginService);
    }

    public function post() {
        // echo "yasmin2";exit;
        $postjson = json_decode(file_get_contents('php://input'),true);
        // print_r($postjson);exit;
        $response = $this->userService->apiPost($postjson);

        return $response;
    }

    public function get($request = null) {
        // print_r($request);exit;
      
        $response = $this->userService->apiGet($request);


        return $response;

    }

    public function put($param) {

        // $param = $request['param'];
        // L�gica para atualizar um usu�rio
        $putJson = json_decode(file_get_contents('php://input'),true);
        $response = $this->userService->apiPut($param, $putJson);

        return $response;
    }

    public function delete($request) {
        // L�gica para deletar um usu�rio
        $param = $request['param'];
        $response = $this->userService->apiDelete($param);
        return $response;
    }
}
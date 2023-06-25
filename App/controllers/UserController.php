<?php

use Service\UserService;

require_once ('./App/services/UserService.php');

class UserController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function post() {
        try{
            $postjson = json_decode(file_get_contents('php://input'),true);
        
            return $this->userService->apiPost($postjson);

        }catch(Exception $e){
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        
    }

    public function get($request = null) {
        try{
            return $this->userService->apiGet($request);

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
            return $this->userService->apiPut($param, $putJson);
        }catch(Exception $e){
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        
    }

    public function delete($param) {
        try{
            return $this->userService->apiDelete($param);
        }catch(Exception $e){
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        
    }
}
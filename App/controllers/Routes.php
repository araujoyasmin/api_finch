<?php

namespace Controller;

use LoginController;
use Service\LoginService;


require_once('./App/controllers/UserController.php');
require_once('./App/services/UserService.php');
require_once('./App/controllers/ProjectController.php');
require_once('./App/services/ProjectService.php');
require_once('./App/controllers/TaskController.php');
require_once('./App/services/TaskService.php');

require_once('./App/controllers/LoginController.php');
require_once('./App/services/LoginService.php');

class Routes{

    private $loginController;
    private $loginService;
    private $secretKey = 'api_finch';

    public function __construct() {
        $this->loginService = new LoginService();
        $this->loginController = new LoginController($this->loginService);
    }

    public  function getRoutes(){
        try{
            if(isset($_GET['path'])){
                $path = explode("/", $_GET['path']);
            }else{
                echo "Rota n�o informada!";exit;
            }
    
            $request = [];
    
            $request['route'] = $path[0];
            $request['param'] = empty($path[1]) ? '':  $path[1];
            $request['method'] = $_SERVER['REQUEST_METHOD'];
    
            if($request['route'] == 'login'){
                return  $this->loginController->login($request);
            }else{
                if($authenticate = $this->loginController->checkAuth()){
                    $route = array_merge($request, $authenticate);
                    return $this->getApi($route);
                }else {
                    $error = [
                        'error' => 'invalid_token',
                        'message' => 'Não autenticado!'
                    ];
                    http_response_code(400); 
                    return $error;
                }           
            }
        }catch(\Exception $e){
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        
    }

    public function getApi($route){

    $perfil = $route['perfil'];
    $method = $route['method'];
    $router = $route['route'];

       switch($route['route']){
            case 'user':
                $routes = [
                    '/user' => [
                        'controller' => 'UserController',
                        'action' => $route['method']
                    ],
                   
                ];
            break;
            case 'project':
                $routes = [
                    '/project' => [
                        'controller' => 'ProjectController',
                        'action' => $route['method']
                    ],
                   
                ];
            break;
            case 'task':
                $routes = [
                    '/task' => [
                        'controller' => 'TaskController',
                        'action' => $route['method']
                    ],
                   
                ];
            break;
            case 'close-task':
                $routes = [
                    '/task' => [
                        'controller' => 'TaskController',
                        'action' => $route['method']
                    ],
                   
                ];
            break;
            case 'close-project':
                $routes = [
                    '/task' => [
                        'controller' => 'ProjectController',
                        'action' => $route['method']
                    ],
                   
                ];
            break;
            default:
                $error = [
                    'error' => 'invalid_route',
                    'message' => 'Rota invalida!'
                ];
                http_response_code(400); 
                return $error;
       }

    
       if(($method != 'GET' && $perfil == 1 && $router != 'close-task') || ($router == 'close-project' && $perfil == 1)){
            $error = [
                'error' => 'invalid_access',
                'message' => 'Seu perfil nao tem acesso a essa rota!'
            ];
            http_response_code(400); 
            return $error;
       }else{
            foreach($routes as $item){
                $controllerClassName = $item['controller'];
                $actionMethodName = $item['action'];
                $controller = new $controllerClassName();
                if (method_exists($controller, $actionMethodName)) {
                    return $controller->$actionMethodName($route['param']);

                } else {
                    $error = [
                        'error' => 'invalid_method',
                        'message' => 'Metodo invalido!'
                    ];
                    http_response_code(400); 
                    return $error;
                }
            }
        }
    }
}
<?php 

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

use Controller\Routes;
// use LoginController;
use Service\LoginService;


require_once ('./App/controllers/Routes.php');
require_once('./App/controllers/LoginController.php');
require_once('./App/services/LoginService.php');
require_once('./App/controllers/FrontController.php');

// $frontController = new FrontController();
// $frontController->handleRequest();


date_default_timezone_set("America/Sao_Paulo");

$loginService = new LoginService();
$loginController = new LoginController($loginService);
$routes = new Routes($loginController);

// $response = $routes->getRoutes();
try {
    $response = $routes->getRoutes(); 
    echo json_encode([
        // 'status' => 'success',
        'data' => $response
    ]);
} catch (\Exception $error) {
    echo json_encode([
        'status' => 'error',
        'message' => $error->getMessage()
    ]);
    die();
}




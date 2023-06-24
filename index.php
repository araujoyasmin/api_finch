<?php 

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
require_once 'autoload.php';

use Controller\Routes;
use Service\LoginService;


require_once ('./App/controllers/Routes.php');
require_once('./App/controllers/LoginController.php');
require_once('./App/services/LoginService.php');


date_default_timezone_set("America/Sao_Paulo");

$loginService = new LoginService();
$loginController = new LoginController($loginService);
$routes = new Routes($loginController);

try {
    $response = $routes->getRoutes(); 
    echo json_encode([
        'data' => $response
    ]);
} catch (\Exception $error) {
    echo json_encode([
        'status' => 'error',
        'message' => $error->getMessage()
    ]);
    die();
}




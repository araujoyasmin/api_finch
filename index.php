<?php 
require('autoload.php');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


use Controller\Routes;

date_default_timezone_set("America/Sao_Paulo");

$routes = new Routes();

try {
    $response = $routes->getRoutes(); 
    echo json_encode($response);
} catch (\Exception $error) {
    echo json_encode([
        'status' => 'error',
        'message' => $error->getMessage()
    ]);
    die();
}




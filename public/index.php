<?php

require '../core/Router.php';
require '../app/controllers/Actividad.php';
require '../app/controllers/codigos.php';

$url = $_SERVER['QUERY_STRING'];

$urlParams = explode('/', $url); // Noscoje la ultima variable url y nos la separa creando una array


$router = new Router();

$router->add('/public/act/get', array(
    'controller' => 'Actividad',    //Llama al controlador
    'action' => 'getAll' //ejecuta el metodo
));

$router->add('/public/act/get/{id}', array(
    'controller' => 'Actividad',
    'action' => 'getById'
));

$router->add('/public/act/create', array(
    'controller' => 'Actividad',
    'action' => 'createAct'
));

$router->add('/public/act/update/{id}', array(
    'controller' => 'Actividad',
    'action' => 'updateAct'
));

$router->add('/public/act/delete/{id}', array(
    'controller' => 'Actividad',
    'action' => 'deleteAct'
));


$urlArray = array(
    'HTTP' => $_SERVER['REQUEST_METHOD'],
    'path' => $url,
    'controller' => '',
    'action' => '',
    'params' => ''
);


//Validaciones por si los diferentes parametros viene vacios para que por defecto obtengan unos valores.

if (!empty($urlParams[2])) {
    $urlArray['controller'] = ucwords($urlParams[2]); //ucwords metodo que coje la primera letra de nuestro string y lo convierte a mayuscula
    if (!empty($urlParams[3])) {
        $urlArray['action'] =  $urlParams[3];
        if (!empty($urlParams[4])) {
            $urlArray['params'] =  $urlParams[4];
        }
    } else {
        $urlArray['action'] = 'index';
    }
} else {
    $urlArray['controller'] = 'Home';
    $urlArray['action'] = 'index';
}



if ($router->matchRoute($urlArray)) {

    $mehod = $_SERVER['REQUEST_METHOD'];

    $params = [];

    if ($mehod == 'GET') {

        $params[] = intval($urlArray['params']) ?? null;
    } elseif ($mehod == 'POST') {

        $json = file_get_contents('php://input');
        $params[] = json_decode($json, true);
    } elseif ($mehod == 'PUT') {

        $id = intval($urlArray['params']) ?? null;
        $json = file_get_contents('php://input');
        $params[] = $id;
        $params[] = json_decode($json, true);
    } elseif ($mehod == 'DELETE') {

        $params[] = intval($urlArray['params']) ?? null;
    }

    $controller = $router->getParams()['controller'];
    $action = $router->getParams()['action'];

    $controller = new $controller();

    if (method_exists($controller, $action)) {
        $resp = call_user_func_array([$controller, $action], $params);
    } else {
        //Devuelve un mensaje de error con el código HTTP 400 (Bad Request)
        HttpCodes::sendResponse(HttpCodes::HTTP_BAD_REQUEST, "Datos del metodo incorrectos.");
    }
} else {
    // Devuelve un mensaje de error con el código HTTP 404 (Not Found)
    HttpCodes::sendResponse(HttpCodes::HTTP_NOT_FOUND, "No se encontro la ruta para la url" . $url);
}

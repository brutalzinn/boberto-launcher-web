<?php
    header('Content-Type: application/json');
    require_once 'vendor/autoload.php';
    include 'route.php';
    $route = new Route();
    //url, controller, method of controller, accept url params
    $route->add('/','Api','index');
    $route->add('/teste','Api','teste', true);
    $route->add('/launcher/modpacks/list','Launcher','list_modpacks');
   
   try {
        http_response_code(200);
        echo json_encode(array('status' => true, 'data' => $route->submit()), JSON_UNESCAPED_UNICODE);
        exit;
    } catch (Exception $e) {
        http_response_code(404);
        echo json_encode(array('status' => false, 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
        exit;
    }

 
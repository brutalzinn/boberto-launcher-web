<?php
    header('Content-Type: application/json');
    require_once 'vendor/autoload.php';
    include 'route.php';
    $route = new Route();
    //url, controller, method of controller, accept url params
    $route->add('/','Api','index');
    $route->add('/teste','Api','teste', true);
    $route->add('/launcher/modpacks/list','Launcher','list_modpacks');
    $route->add('/launcher/config','Launcher','launcher_Config');
    $route->add('/launcher/modpacks/upload','Launcher','uploadFile');
    $route->add('/launcher/version/upload','Launcher','uploadLauncherZips');
    $route->add('/launcher/version','Launcher','updateLauncherVersion');
    $route->add('/modpackcreator/modpacks/sync','ModPackManager','syncModPack');
    $route->add('/modpackcreator/modpacks/append','ModPackManager','appendModPack');


    try{
        http_response_code(200);
        echo json_encode(array('status' => true, 'data' => $route->submit()), JSON_UNESCAPED_UNICODE);
        exit;
    } catch (Exception $e) {
        http_response_code(404);
        echo json_encode(array('status' => false, 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
        exit;
    }

 
<?php
    header('Content-Type: application/json');
    require_once 'vendor/autoload.php';
    use App\Services\RedisService;


    include 'route.php';
    Predis\Autoloader::register();
    $client = new Predis\Client([
        'scheme' => 'tcp',
        'host'   => getenv("BOBERTO_HOST"),
        'port'   => 6379,
        'password' => getenv("REDIS_PASSWORD")
    ]);
    RedisService::Init($client);
    $api_key = getenv('API_HEADER');



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
    $route->add('/redis/del','Redis','delRedis');
    $route->add('/redis/clear','Redis','clearRedis');
    #if the content has id, the news will be updated
    $route->add('/launcher/news','News','updateNews');
    $route->add('/launcher/news/del','News','deleteNews');


    try
    {
        $request_headers = getallheaders();
        if(!isset($request_headers[$api_key]) || isset($request_headers[$api_key]) && $request_headers[$api_key] != getenv('API_TOKEN')) {
            http_response_code(401);
            echo json_encode(array('status' => false, 'data' => 'API-KEY DONT PROVIDED OR API-KEY IS WRONG.'), JSON_UNESCAPED_UNICODE);
            exit;
        }
        http_response_code(200);
        echo json_encode(array('status' => true, 'data' => $route->submit()), JSON_UNESCAPED_UNICODE);
        exit;
    } catch (Exception $e) {
        http_response_code(404);
        echo json_encode(array('status' => false, 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
        exit;
    }

 
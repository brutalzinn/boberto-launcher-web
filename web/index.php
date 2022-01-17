<?php
    header('Content-Type: application/json');
    require_once 'vendor/autoload.php';
    use App\Services\RedisService;
    use App\Controllers;
use App\Controllers\ApiController;
use App\Controllers\LauncherController;

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

    define('BASEPATH','/');


    
    //url, controller, method of controller, accept url params
    Route::add('/',fn()=> ApiController::index(),'get');

    Route::add('/launcher/modpacks/list',fn()=> LauncherController::list_modpacks(),'get');
    Route::add('/launcher/config',fn()=> LauncherController::launcher_Config(),'post');
    Route::add('/launcher/modpacks/upload',fn()=> LauncherController::uploadFile(),'post');
    Route::add('/launcher/version/upload',fn()=> LauncherController::uploadLauncherZips(),'post');
    Route::add('/launcher/version','Launcher',fn()=> LauncherController::updateLauncherVersion(),'post');
    Route::add('/modpackcreator/modpacks/sync','ModPackManager','syncModPack');
    Route::add('/modpackcreator/modpacks/append','ModPackManager','appendModPack');
    Route::add('/redis/del','Redis','delRedis');
    Route::add('/redis/clear','Redis','clearRedis');
    #if the content has id, the news will be updated
    Route::add('/launcher/news/update','News','updateNews');
    Route::add('/launcher/news','News','readNews', true);
    Route::add('/launcher/news/del','News','deleteNews');


    Route::pathNotFound(function($path) {
        // Do not forget to send a status header back to the client
        // The router will not send any headers by default
        // So you will have the full flexibility to handle this case
        header('HTTP/1.0 404 Not Found');
       
        echo 'Error 404 :-(<br>';
        echo 'The requested path "'.$path.'" was not found!';
      });
      
      // Add a 405 method not allowed route
      Route::methodNotAllowed(function($path, $method) {
        // Do not forget to send a status header back to the client
        // The router will not send any headers by default
        // So you will have the full flexibility to handle this case
        header('HTTP/1.0 405 Method Not Allowed');
        echo 'Error 405 :-(<br>';
        echo 'The requested path "'.$path.'" exists. But the request method "'.$method.'" is not allowed on this path!';
      });

    try
    {
        // $request_headers = getallheaders();
        // if(!isset($request_headers[$api_key]) || isset($request_headers[$api_key]) && $request_headers[$api_key] != getenv('API_TOKEN')) {
        //     http_response_code(401);
        //     echo json_encode(array('status' => false, 'data' => 'API-KEY DONT PROVIDED OR API-KEY IS WRONG.'), JSON_UNESCAPED_UNICODE);
        //     exit;
        // }
        Route::run(BASEPATH);
        exit;
    } catch (Exception $e) {
        http_response_code(404);
        echo json_encode(array('status' => false, 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
        exit;
    }

 
<?php
    header('Content-Type: application/json');
    require_once 'vendor/autoload.php';

    use App\Services\RedisService;
    use App\Controllers\ApiController;
    use App\Controllers\LauncherController;
    use App\Controllers\ModPackManagerController;
    use App\Controllers\NewsController;
    use App\Controllers\RedisController;

    include 'route.php';
    RedisService::Init($client);

    define('BASEPATH','/');
    define('API_KEY',getenv("API_HEADER"));
    define('HOST',getenv("BOBERTO_HOST"));
    define('DATABASE',getenv("BOBERTO_DATABASE"));
    define('USER',getenv("BOBERTO_USER"));
    define('PASSWORD',getenv("BOBERTO_PASSWORD"));
    define('JWT_SECRET',getenv("JWT_SECRET"));
    define('PORT',getenv("BOBERTO_PORT"));
    define('HOSTSTRING',"host=".HOST." port=".PORT." dbname=".DATABASE." user=".USER." password=".PASSWORD."");

    //url, controller, method of controller, accept url params
    Route::add('/',fn()=> ApiController::index(),'get');

    Route::add('/launcher/modpacks/list',fn()=> LauncherController::list_modpacks(),'get');
    Route::add('/launcher/config',fn()=> LauncherController::launcher_Config(),'post');
    Route::add('/launcher/modpacks/upload',fn()=> LauncherController::uploadFile(),'post');
    Route::add('/launcher/version/upload',fn()=> LauncherController::uploadLauncherZips(),'post');
    Route::add('/launcher/version', fn()=> LauncherController::updateLauncherVersion(),['get','post'], false, true);

   
    Route::add('/modpackcreator/modpacks/sync', fn()=> ModPackManagerController::syncModPack(),'post');
    Route::add('/modpackcreator/modpacks/append', fn()=> ModPackManagerController::appendModPack(),'post');
    Route::add('/modpackcreator/cliente/modpack/(.*)', fn($id)=> ModPackManagerController::getModPackFileInfo($id), ['get'], true, true);

    Route::add('/redis/del', fn()=> RedisController::delRedis(),'post');
    Route::add('/redis/clear', fn()=> RedisController::clearRedis(),'post');
    //wrong way to do this.
    Route::add('/launcher/news/update', fn()=> NewsController::AddOrUpdateNews(),'post');
    Route::add('/launcher/news/page/(.*)/limit/(.*)', fn($page, $limit)=> NewsController::readNews($page, $limit),'get');
    Route::add('/launcher/news/del', fn()=> NewsController::deleteNews(),'post');


    Route::pathNotFound(function($path) {

        header('HTTP/1.0 404 Not Found');
       
        echo 'Error 404 :-(<br>';
        echo 'The requested path "'.$path.'" was not found!';
      });
      

      Route::methodNotAllowed(function($path, $method) {

        header('HTTP/1.0 405 Method Not Allowed');
        echo 'Error 405 :-(<br>';
        echo 'The requested path "'.$path.'" exists. But the request method "'.$method.'" is not allowed on this path!';
      });

    try
    {
        //This need be changed early. Because a Bcrypt to handle a user like a security for api key..
        //its useless.
        Route::run(BASEPATH);
        exit;
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(array('status' => false, 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
        exit;
    }

 
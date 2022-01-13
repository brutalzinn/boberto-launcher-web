<?php
    header('Content-Type: application/json');

    require_once 'vendor/autoload.php';

    // api/users/1
    if ($_SERVER['REQUEST_URI']) {
        $url = explode('/', $_SERVER['REQUEST_URI']);
        if ($url[1] === 'api') {
            array_shift($url);
            $service = 'App\Services\\'.ucfirst($url[1]).'Service';
            if(class_exists($service) == false){
                http_response_code(400);
                echo json_encode(array('status' => 'error', 'data' => "Controller: " . $url[1] . " Not found"), JSON_UNESCAPED_UNICODE);
                exit;
            }
            array_shift($url);
            $method = strtolower($_SERVER['REQUEST_METHOD']);
            if(count($url) == 1){
                echo json_encode(array('status' => 'error', 'data' => 'route not found'), JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            try {
                
                $serviceInstance = new $service($method);

                if(!method_exists($serviceInstance, $url[1]) && !is_callable(array($serviceInstance, $url[1])))
                {
                    throw new Exception("Route not found on controller: ". $url[0]);
                }
                $response = call_user_func_array(array(new $serviceInstance, $url[1]), $url);
                http_response_code(200);
                echo json_encode(array('status' => 'sucess', 'data' => $response),JSON_UNESCAPED_UNICODE);
                exit;
            } catch (\Exception $e) {
                http_response_code(404);
                echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }
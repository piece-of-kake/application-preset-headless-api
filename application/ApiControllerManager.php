<?php

namespace App;

class ApiControllerManager {
    private $app;
    
    public function __construct($app)
    {
        $this->app = $app;
    }
    
    public function any($route, $class)
    {
        $this->app->any($route, function ($request, $response, $args) use ($class) {
            $controller = new $class($request, $this);
            $controllerResponse = $controller(...array_values($args));
            $response->getBody()->write(json_encode($controllerResponse->getContent()));
            $response = $response->withStatus($controllerResponse->getCode()->getValue());
            foreach ($controllerResponse->getHeaders() as $name => $value) {
                $response = $response->withHeader($name, $value);
            }
            return $response;
        });
    }
}

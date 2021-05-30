<?php

namespace App;

use PoK\ValueObject\TypeString;

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
            $controllerResponse = $controller(new TypeString(array_shift($args)), new TypeString(array_shift($args)));
            $response->getBody()->write(json_encode($controllerResponse->getContent()));
            $response = $response->withStatus($controllerResponse->getCode()->getValue());
            foreach ($controllerResponse->getHeaders() as $name => $value) {
                $response = $response->withHeader($name, $value);
            }
            return $response;
        });
    }
}

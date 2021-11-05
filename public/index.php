<?php

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$config = (new \Gouh\BlogApi\ConfigProvider())();

try {
    $router = new \Gouh\BlogApi\Router\Router($config['routes']);
    $route = $router->matchFromPath($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

    $parameters = $route->getParameters();
    $arguments = $route->getVars();

    $handlerName = $parameters[0];
    $methodName = $parameters[1] ?? null;

    $handler = new $handlerName();
    if (!is_callable($handler)) {
        $handler =  [$handler, $methodName];
    }

    $handler(...array_values($arguments));
} catch (\Exception $exception) {
    header("HTTP/1.0 404 Not Found");
}

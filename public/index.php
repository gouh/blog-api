<?php

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

try {
    $config = require 'config/local.php';

    $container = new \Gouh\BlogApi\Container\Container($config['dependencies']);
    $router = new \Gouh\BlogApi\Router\Router($config['routes']);
    $route = $router->matchFromPath($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

    $parameters = $route->getParameters();
    $arguments = $route->getVars();

    $handlerName = $parameters[0];
    $methodName = $parameters[1] ?? null;

    try {
        $handler = $container->get($handlerName);
    } catch (\Throwable $e) {
        \Gouh\BlogApi\Response\ServerResponse::JsonResponse([
            'message' => 'Error in container',
            'data' => [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTrace()
            ]
        ], 500);
    }

    if (!is_callable($handler)) {
        $handler = [$handler, $methodName];
    }

    $handler(\Gouh\BlogApi\Request\ServerRequest::fromGlobals($arguments));
} catch (\Exception $e) {
    header("HTTP/1.0 404 Not Found");
} catch (\Psr\Container\ContainerExceptionInterface $e) {
    header("HTTP/1.0 500 Internal Server Error");
}

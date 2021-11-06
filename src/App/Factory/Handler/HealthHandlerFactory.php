<?php

namespace Gouh\BlogApi\App\Factory\Handler;

use Exception;
use Gouh\BlogApi\App\Handler\HealthHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class HealthHandlerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): HealthHandler
    {
        try {
            $config = $container->get('config');
            return new HealthHandler($config);
        }catch (Exception $e) {
            echo "<pre>";
            print_r($e->getMessage());
            echo "<pre>";
            exit();
        }
    }
}

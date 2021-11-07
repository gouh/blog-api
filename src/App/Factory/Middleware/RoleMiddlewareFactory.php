<?php

namespace Gouh\BlogApi\App\Factory\Middleware;

use Gouh\BlogApi\App\Middleware\RoleMiddleware;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class RoleMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     * @return RoleMiddleware
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RoleMiddleware
    {
        $config = $container->get('config');
        return new RoleMiddleware($config);
    }
}

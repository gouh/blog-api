<?php

namespace Gouh\BlogApi\App\Factory\Handler;

use Gouh\BlogApi\App\Handler\LoginHandler;
use Gouh\BlogApi\App\Service\LoginService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class LoginHandlerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): LoginHandler
    {
        $loginService = $container->get(LoginService::class);
        return new LoginHandler($loginService);
    }
}

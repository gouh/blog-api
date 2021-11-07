<?php

namespace Gouh\BlogApi\App\Factory\Handler;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Gouh\BlogApi\App\Handler\RegisterHandler;
use Gouh\BlogApi\App\Service\UserService;

class RegisterHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return RegisterHandler
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RegisterHandler
    {
        $userService = $container->get(UserService::class);
        return new RegisterHandler($userService);
    }
}

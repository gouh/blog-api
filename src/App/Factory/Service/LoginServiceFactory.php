<?php

namespace Gouh\BlogApi\App\Factory\Service;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Gouh\BlogApi\App\DAO\UserDAO;
use Gouh\BlogApi\App\Service\LoginService;

class LoginServiceFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): LoginService
    {
        $userService = $container->get(UserDAO::class);
        $config = $container->get('config');
        return new LoginService($userService, $config);
    }
}

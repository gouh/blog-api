<?php

namespace Gouh\BlogApi\App\Factory\Service;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Gouh\BlogApi\App\DAO\RoleDao;
use Gouh\BlogApi\App\DAO\UserDAO;
use Gouh\BlogApi\App\DTO\UserDTO;
use Gouh\BlogApi\App\Service\UserService;

class UserServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return UserService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): UserService
    {
        $userDao = $container->get(UserDAO::class);
        $roleDao = $container->get(RoleDAO::class);
        $userDto = $container->get(UserDTO::class);
        return new UserService($userDao, $roleDao, $userDto);
    }
}

<?php

namespace Gouh\BlogApi\App;

use Gouh\BlogApi\Router\Route;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'routes' => $this->getRoutes(),
            'dependencies' => $this->getDependencies(),
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factory' => [
                # Database
                \PDO::class => \Gouh\BlogApi\App\Factory\Database\PDOFactory::class,

                # Handler
                \Gouh\BlogApi\App\Handler\HealthHandler::class => \Gouh\BlogApi\App\Factory\Handler\HealthHandlerFactory::class,
                \Gouh\BlogApi\App\Handler\RegisterHandler::class => \Gouh\BlogApi\App\Factory\Handler\RegisterHandlerFactory::class,
                \Gouh\BlogApi\App\Handler\PostHandler::class => \Gouh\BlogApi\App\Factory\Handler\PostHandlerFactory::class,

                # Service
                \Gouh\BlogApi\App\Service\UserService::class => \Gouh\BlogApi\App\Factory\Service\UserServiceFactory::class,
                \Gouh\BlogApi\App\Service\PostService::class => \Gouh\BlogApi\App\Factory\Service\PostServiceFactory::class,
            ]
        ];
    }

    private function getRoutes(): array
    {
        return [
            new Route('health', '/health', [\Gouh\BlogApi\App\Handler\HealthHandler::class, 'get'], ['GET']),
            new Route('register', '/register', [\Gouh\BlogApi\App\Handler\RegisterHandler::class, 'post'], ['POST']),
            new Route('list.posts', '/posts', [\Gouh\BlogApi\App\Handler\PostHandler::class, 'getAll'], ['GET']),
            new Route('add.post', '/posts', [\Gouh\BlogApi\App\Handler\PostHandler::class, 'post'], ['POST']),
            new Route('update.post', '/posts/{postId}', [\Gouh\BlogApi\App\Handler\PostHandler::class, 'update'], ['PUT']),
            new Route('delete.post', '/posts/{postId}', [\Gouh\BlogApi\App\Handler\PostHandler::class, 'delete'], ['DELETE']),
        ];
    }
}

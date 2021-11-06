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
                \Gouh\BlogApi\App\Handler\HealthHandler::class => \Gouh\BlogApi\App\Factory\Handler\HealthHandlerFactory::class,
            ]
        ];
    }

    private function getRoutes(): array
    {
        return [
            new Route('health', '/health', [\Gouh\BlogApi\App\Handler\HealthHandler::class, 'get'], ['GET']),
        ];
    }
}

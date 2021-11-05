<?php

namespace Gouh\BlogApi;

use Gouh\BlogApi\Router\Route;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'routes' => $this->setRoutes()
        ];
    }

    private function setRoutes(): array
    {
        return [
            new Route('health', '/health', [\Gouh\BlogApi\Handler\HealthHandler::class], ['GET']),
        ];
    }
}

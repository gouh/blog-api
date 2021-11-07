<?php

namespace Gouh\BlogApi\App\Factory\Handler;

use Gouh\BlogApi\App\Handler\HealthHandler;
use Psr\Container\ContainerInterface;

class HealthHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return HealthHandler
     */
    public function __invoke(ContainerInterface $container): HealthHandler
    {
        return new HealthHandler();
    }
}

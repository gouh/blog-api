<?php

namespace Gouh\BlogApi\App\Factory\Handler;

use Gouh\BlogApi\App\Handler\HealthHandler;
use Psr\Container\ContainerInterface;

class HealthHandlerFactory
{
    public function __invoke(ContainerInterface $container): HealthHandler
    {
        return new HealthHandler();
    }
}

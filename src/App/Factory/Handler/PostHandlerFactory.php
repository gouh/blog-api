<?php

namespace Gouh\BlogApi\App\Factory\Handler;

use Gouh\BlogApi\App\Factory\Middleware\RoleMiddlewareFactory;
use Gouh\BlogApi\App\Handler\PostHandler;
use Gouh\BlogApi\App\Service\PostService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class PostHandlerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): PostHandler
    {
        $postService = $container->get(PostService::class);
        $roleMiddleware = $container->get(RoleMiddlewareFactory::class);
        return new PostHandler($postService, $roleMiddleware);
    }
}

<?php

namespace Gouh\BlogApi\App\Factory\Service;

use Gouh\BlogApi\App\DAO\PostDAO;
use Gouh\BlogApi\App\DTO\PostDTO;
use Gouh\BlogApi\App\Service\PostService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class PostServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return PostService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): PostService
    {
        $postDao = $container->get(PostDAO::class);
        $postDto = $container->get(PostDTO::class);
        return new PostService($postDao, $postDto);
    }
}

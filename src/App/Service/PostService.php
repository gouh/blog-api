<?php

namespace Gouh\BlogApi\App\Service;

use Gouh\BlogApi\App\DAO\InterfaceDAO;
use Gouh\BlogApi\App\DAO\InterfacePaginationDAO;
use Gouh\BlogApi\App\DTO\InterfaceDTO;
use Gouh\BlogApi\App\Traits\PaginateTrait;

class PostService
{
    use PaginateTrait;

    private InterfaceDAO $postDao;
    private InterfaceDTO $postDto;

    public function __construct(InterfacePaginationDAO $postDao, InterfaceDTO $postDto)
    {
        $this->postDao = $postDao;
        $this->postDto = $postDto;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getAll(array $params): array
    {
        $page = (int)($params['page'] ?? 1);
        $pageSize = (int)($params['page_size'] ?? 20);

        # offset calc
        $page = ($page < 1) ? 1 : $page;
        $offset = $pageSize * ($page - 1);

        $results = $this->postDao->findAllPagination($pageSize, $offset);
        $countPosts = $this->postDao->countPagination();
        $countPosts = !empty($countPosts) ? $countPosts['postCount'] : 0;
        return [
            'posts' => $this->postDto->map($results, \Gouh\BlogApi\App\DTO\Strategy\PostArrayStrategy::class),
            'paginate' => $this->paginate($page, $pageSize, $countPosts, sizeof($results))
        ];
    }
}

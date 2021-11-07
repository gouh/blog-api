<?php

namespace Gouh\BlogApi\App\Service;

use Exception;
use Gouh\BlogApi\App\DAO\InterfaceDAO;
use Gouh\BlogApi\App\DAO\InterfacePaginationDAO;
use Gouh\BlogApi\App\DTO\InterfaceDTO;
use Gouh\BlogApi\App\DTO\Strategy\PostArrayStrategy;
use Gouh\BlogApi\App\DTO\Strategy\PostArrayUniqueStrategy;
use Gouh\BlogApi\App\DTO\Strategy\PostObjectStrategy;
use Gouh\BlogApi\App\Entity\Post;
use Gouh\BlogApi\App\Traits\PaginateTrait;
use PDOException;

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
            'posts' => $this->postDto->map($results, PostArrayStrategy::class),
            'paginate' => $this->paginate($page, $pageSize, $countPosts, sizeof($results))
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function save(array $data): array
    {
        /** @var Post $post */
        $post = $this->postDto->map($data, PostObjectStrategy::class);
        /** @var Post $post */
        $post = $this->postDao->save($post);
        return $this->postDto->map($post, PostArrayUniqueStrategy::class);
    }

    /**
     * @param int $postId
     * @param array $data
     * @return array
     * @throws Exception|PDOException
     */
    public function update(int $postId, array $data): array
    {
        $data['postId'] = $postId;
        /** @var Post $post */
        $post = $this->postDto->map($data, PostObjectStrategy::class);
        /** @var Post $post */
        $post = $this->postDao->update($post);
        return $this->postDto->map($post, PostArrayUniqueStrategy::class);
    }

    /**
     * @param int $postId
     * @return bool
     * @throws Exception
     */
    public function delete(int $postId): bool
    {
        return $this->postDao->delete($postId);
    }
}

<?php

namespace Gouh\BlogApi\App\DTO\Strategy;

use Gouh\BlogApi\App\Entity\Post;

class PostArrayUniqueStrategy implements InterfaceStrategy
{
    /**
     * @param Post $post
     * @return array
     */
    public function mapPost(Post $post): array
    {
        return [
            'postId' => $post->getPostId(),
            'title' => $post->getTitle(),
            'description' => $post->getDescription(),
            'createdAt' => $post->getCreatedAt()
        ];
    }

    /**
     * @param Post|array $data
     * @return mixed
     */
    public function build($data): array
    {
        return $this->mapPost($data);
    }
}

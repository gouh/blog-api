<?php

namespace Gouh\BlogApi\App\DTO\Strategy;

use Gouh\BlogApi\App\Entity\Post;

class PostObjectStrategy
{
    /**
     * @param array $data
     * @return Post
     */
    private function arrayToPost(array $data): Post
    {
        $post = new Post;
        $post->setTitle($data['title'] ?? '');
        $post->setDescription($data['description'] ?? '');
        $post->setUserId($data['userId'] ?? 0);
        $post->setPostId($data['postId'] ?? 0);
        return $post;
    }

    /**
     * @param array|object $data
     * @return mixed
     */
    public function build($data)
    {
        return $this->arrayToPost($data);
    }
}

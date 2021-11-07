<?php

namespace Gouh\BlogApi\App\DTO\Strategy;

use Gouh\BlogApi\App\Entity\User;

class PostArrayStrategy implements InterfaceStrategy
{

    /**
     * @param array $post
     * @return array
     */
    public function mapPost(array $post): array
    {
        return [
            'postId' => $post['postId'],
            'title' => $post['title'],
            'description' => $post['description'],
            'createdAt' => $post['createdAt'],
            'user' => [
                'userId' => $post['userId'],
                'name' => $post['userName'],
                'role' => $post['userRole'],
            ]
        ];
    }

    /**
     * @param $posts
     * @return array
     */
    private function usersToArray($posts): array
    {
        return array_map(function ($user) {
            return $this->mapPost($user);
        }, $posts);
    }


    /**
     * @param object|array $data
     * @return mixed
     */
    public function build($data): array
    {
        $result = [];
        if (is_array($data)) {
            $result = $this->usersToArray($data);
        }
        return $result;
    }
}

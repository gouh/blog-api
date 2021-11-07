<?php

namespace Gouh\BlogApi\App\Handler;

use Exception;
use Gouh\BlogApi\App\Service\PostService;
use Gouh\BlogApi\Request\ServerRequest;
use Gouh\BlogApi\Response\ServerResponse;

class PostHandler
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @param ServerRequest $serverRequest
     */
    public function getAll(ServerRequest $serverRequest)
    {
        try {
            $result = $this->postService->getAll($serverRequest->getQueryParams());
            if (!empty($result['posts'])) {
                ServerResponse::JsonResponse([
                    'message' => 'Posts found.',
                    'data' => $result['posts'],
                    'paginate' => $result['paginate'],
                ]);
            }
            ServerResponse::JsonResponse([
                'message' => 'Posts not found.',
                'data' => [],
            ], 404);
        } catch (Exception $e) {
            ServerResponse::JsonResponse([
                'message' => 'Failed to create a posts, please try again',
                'data' => [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode()
                ]
            ], 500);
        }
    }
}

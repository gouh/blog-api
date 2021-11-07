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
                'paginate' => [],
            ], 404);
        } catch (Exception $e) {
            ServerResponse::JsonResponse([
                'message' => 'Failed to find posts',
                'data' => [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode()
                ],
                'paginate' => [],
            ], $e->getCode() < 600 ? $e->getCode() : 500);
        }
    }

    public function post(ServerRequest $serverRequest)
    {
        try {
            $post = $this->postService->save($serverRequest->getParsedBody());
            if (!empty($post)) {
                ServerResponse::JsonResponse([
                    'message' => 'Post created successfully.',
                    'data' => $post,
                    'paginate' => [],
                ]);
            }
            ServerResponse::JsonResponse([
                'message' => 'Posts could not be created, please try again.',
                'data' => [],
                'paginate' => [],
            ], 400);
        } catch (Exception $e) {
            ServerResponse::JsonResponse([
                'message' => 'Failed to create post',
                'data' => [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode()
                ],
                'paginate' => [],
            ], $e->getCode() < 600 ? $e->getCode() : 500);
        }
    }

    public function update(ServerRequest $serverRequest)
    {
        try {
            $postId = (int) $serverRequest->getAttribute('postId');
            $post = $this->postService->update($postId, $serverRequest->getParsedBody());
            if (!empty($post)) {
                ServerResponse::JsonResponse([
                    'message' => 'Post updated successfully.',
                    'data' => $post,
                    'paginate' => [],
                ]);
            }
            ServerResponse::JsonResponse([
                'message' => 'Post could not be updated, please try again.',
                'data' => [],
                'paginate' => [],
            ], 400);
        } catch (Exception $e) {
            ServerResponse::JsonResponse([
                'message' => 'Failed to updated post',
                'data' => [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode()
                ],
                'paginate' => [],
            ], $e->getCode() < 600 ? $e->getCode() : 500);
        }
    }

    public function delete(ServerRequest $serverRequest)
    {
        try {
            $postId = (int) $serverRequest->getAttribute('postId');
            $deleted = $this->postService->delete($postId);
            if ($deleted) {
                ServerResponse::JsonResponse([
                    'message' => 'Post deleted successfully.',
                    'data' => [],
                    'paginate' => [],
                ], 202);
            }
            ServerResponse::JsonResponse([
                'message' => 'Post could not be deleted, please try again.',
                'data' => [],
                'paginate' => [],
            ], 400);
        } catch (Exception $e) {
            ServerResponse::JsonResponse([
                'message' => 'Failed to delete post',
                'data' => [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode()
                ],
                'paginate' => [],
            ], $e->getCode() < 600 ? $e->getCode() : 500);
        }
    }
}

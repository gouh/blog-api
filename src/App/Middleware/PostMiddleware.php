<?php

namespace Gouh\BlogApi\App\Middleware;

use Gouh\BlogApi\Request\ServerRequest;
use Gouh\BlogApi\Response\ServerResponse;

class PostMiddleware
{
    /**
     * @param ServerRequest $request
     */
    public function process(ServerRequest $request)
    {
        $user = $request->getParsedBody();
        $required = ['title', 'description'];
        foreach ($required as $key => $value) {
            if (isset($user[$value])) {
                unset($required[$key]);
            }
        }
        if (!empty($required)) {
            ServerResponse::JsonResponse([
                'message' => 'The fields title, description are required.',
                'data' => [],
            ], 400);
        }
    }
}

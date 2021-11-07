<?php

namespace Gouh\BlogApi\App\Middleware;

use Gouh\BlogApi\Request\ServerRequest;
use Gouh\BlogApi\Response\ServerResponse;

class LoginMiddleware
{
    /**
     * @param ServerRequest $request
     */
    public function process(ServerRequest $request)
    {
        $user = $request->getParsedBody();
        $required = ['email', 'password'];
        foreach ($required as $key => $value) {
            if (isset($user[$value])) {
                unset($required[$key]);
            }
        }
        if (!empty($required)) {
            ServerResponse::JsonResponse([
                'message' => 'The fields email, password are required.',
                'data' => [],
            ], 400);
        }
    }
}

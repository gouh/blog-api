<?php

namespace Gouh\BlogApi\App\Middleware;

use Gouh\BlogApi\Request\ServerRequest;
use Gouh\BlogApi\Response\ServerResponse;

class UserMiddleware
{
    /**
     * @param ServerRequest $request
     */
    public function process(ServerRequest $request)
    {
        $user = $request->getParsedBody();
        $required = ['name', 'lastName', 'email', 'password', 'roleId'];
        foreach ($required as $key => $value) {
            if (isset($user[$value])) {
                unset($required[$key]);
            }
        }
        if (!empty($required)) {
            ServerResponse::JsonResponse([
                'message' => 'The fields name, lastName, email, password, roleId are required.',
                'data' => [],
            ], 400);
        }
    }
}

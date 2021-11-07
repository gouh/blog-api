<?php

namespace Gouh\BlogApi\App\Handler;

use Exception;
use Gouh\BlogApi\App\Service\LoginService;
use Gouh\BlogApi\Request\ServerRequest;
use Gouh\BlogApi\Response\ServerResponse;

class LoginHandler
{

    /** @var LoginService */
    private LoginService $loginService;

    /**
     * @param LoginService $loginService
     */
    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    /**
     * @param ServerRequest $serverRequest
     */
    public function post(ServerRequest $serverRequest)
    {
        try {
            $jwt = $this->loginService->login($serverRequest->getParsedBody());
            if (!empty($jwt)) {
                ServerResponse::JsonResponse([
                    'message' => 'Login successfully.',
                    'data' => $jwt,
                    'paginate' => [],
                ]);
            }
            ServerResponse::JsonResponse([
                'message' => 'User or password are incorrectly.',
                'data' => [],
                'paginate' => [],
            ], 403);
        } catch (Exception $e) {
            ServerResponse::JsonResponse([
                'message' => 'Failed to login',
                'data' => [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode()
                ],
                'paginate' => [],
            ], $e->getCode() < 600 ? $e->getCode() : 500);
        }
    }
}

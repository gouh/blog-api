<?php

namespace Gouh\BlogApi\App\Handler;

use Exception;
use Gouh\BlogApi\App\Middleware\UserMiddleware;
use Gouh\BlogApi\App\Service\UserService;
use Gouh\BlogApi\Request\ServerRequest;
use Gouh\BlogApi\Response\ServerResponse;

class RegisterHandler
{
    /** @var UserService  */
    private UserService $userService;

    /** @var UserMiddleware  */
    private UserMiddleware $userMiddleware;

    public function __construct(UserService $userService, UserMiddleware $userMiddleware)
    {
        $this->userService = $userService;
        $this->userMiddleware = $userMiddleware;
    }

    /**
     * @param ServerRequest $request
     */
    public function post(ServerRequest $request)
    {
        try {
            $this->userMiddleware->process($request);
            $user = $this->userService->save($request->getParsedBody());
            if (!empty($user)) {
                ServerResponse::JsonResponse([
                    'message' => 'User created successfully.',
                    'data' => $user,
                ]);
            }
            ServerResponse::JsonResponse([
                'message' => 'User could not be created, please try again.',
                'data' => [],
            ], 400);
        } catch (Exception $e) {
            ServerResponse::JsonResponse([
                'message' => 'Failed to create a user, please try again',
                'data' => [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode()
                ]
            ], 500);
        }
    }
}

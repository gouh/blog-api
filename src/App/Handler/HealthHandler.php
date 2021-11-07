<?php

namespace Gouh\BlogApi\App\Handler;

use Gouh\BlogApi\Request\ServerRequest;
use Gouh\BlogApi\Response\ServerResponse;

class HealthHandler
{
    /**
     * @param ServerRequest $request
     */
    public function get(ServerRequest $request)
    {
        ServerResponse::JsonResponse([
            "php" => phpversion()
        ]);
    }
}

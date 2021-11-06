<?php

namespace Gouh\BlogApi\App\Handler;

use Gouh\BlogApi\Request\ServerRequest;
use Gouh\BlogApi\Response\ServerResponse;

class HealthHandler
{
    public function get(ServerRequest $request)
    {
        ServerResponse::JsonResponse([
            "php" => phpversion(),
            "queryParams" => $request->getQueryParams(),
        ]);
    }
}

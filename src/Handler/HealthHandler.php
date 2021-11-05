<?php

namespace Gouh\BlogApi\Handler;

class HealthHandler
{
    public function __invoke()
    {
        echo json_encode([
            "php" => phpversion()
        ]);
    }
}

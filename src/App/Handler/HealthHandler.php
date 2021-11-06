<?php

namespace Gouh\BlogApi\App\Handler;

class HealthHandler
{
    public function get()
    {
        echo json_encode([
            "php" => phpversion()
        ]);
    }
}

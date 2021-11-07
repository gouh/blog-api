<?php

namespace Gouh\BlogApi\App\DTO;

use Gouh\BlogApi\App\DTO\Strategy\InterfaceStrategy;

abstract class AbstractDTO implements InterfaceDTO
{
    /**
     * @param object|array $data
     * @param string $strategy
     * @return mixed
     */
    public function map($data, string $strategy)
    {
        $strategy = new $strategy();
        return $strategy->build($data);
    }
}

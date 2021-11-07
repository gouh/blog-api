<?php

namespace Gouh\BlogApi\App\DTO;

use Gouh\BlogApi\App\DTO\Strategy\InterfaceStrategy;

interface InterfaceDTO
{
    /**
     * @param object|array $data
     * @param String $strategy
     * @return mixed
     */
    public function map($data, String $strategy);
}

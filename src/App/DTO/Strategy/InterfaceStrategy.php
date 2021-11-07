<?php

namespace Gouh\BlogApi\App\DTO\Strategy;

interface InterfaceStrategy
{
    /**
     * @param object|array $data
     * @return mixed
     */
    public function build($data);
}

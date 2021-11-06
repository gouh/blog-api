<?php

namespace Gouh\BlogApi\App\DAO;

interface InterfacePaginationDAO extends InterfaceDAO
{
    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAllPagination(int $limit, int $offset): array;
}

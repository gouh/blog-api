<?php

namespace Gouh\BlogApi\App\DAO;

interface DAOPaginationInterface extends DAOInterface
{
    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAllPagination(int $limit, int $offset): array;
}

<?php

namespace Gouh\BlogApi\App\DAO;

interface InterfacePaginationDAO
{
    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAllPagination(int $limit, int $offset): array;

    /**
     * @return array|null
     */
    public function countPagination(): ?array;
}

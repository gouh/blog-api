<?php

namespace Gouh\BlogApi\App\DAO;

interface InterfaceDAO
{
    /**
     * @param int $id
     * @return object|null
     */
    public function find(int $id): ?object;

    /**
     * @param array
     * @return object[]
     */
    public function findBy(array $filter): array;

    /**
     * @return object[]
     */
    public function findAll(): array;

    /**
     * @param object $entity
     * @return object
     */
    public function save(object $entity): object;

    /**
     * @param object $entity
     * @return object
     */
    public function update(object $entity): object;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}

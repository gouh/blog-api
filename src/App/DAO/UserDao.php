<?php

namespace Gouh\BlogApi\App\DAO;

use Gouh\BlogApi\App\Entity\User;
use PDO;

class UserDao implements DAOPaginationInterface
{
    /** @var PDO */
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function find(int $id): ?object
    {
        $user = null;
        $stmt = $this->connection->prepare("SELECT * FROM user WHERE user_id=:id");
        if ($stmt) {
            $stmt->execute(['id' => $id]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
            $user = $stmt->fetch();
        }
        return $user;
    }

    /**
     * @param $filter
     * @return string
     */
    private function getWhereSql($filter): string
    {
        $whereSql = [];
        foreach ($filter as $key => $value){
            $whereSql[] = "$key = :$key";
        }
        return implode(" AND ", $whereSql);
    }

    /**
     * @param array $filter
     * @return object[]
     */
    public function findBy(array $filter): array
    {
        $users = [];
        $sql = "SELECT * FROM user WHERE " . $this->getWhereSql($filter);
        $stmt = $this->connection->prepare($sql);
        if ($stmt) {
            foreach ($filter as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
        }

        return $users;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $user = [];
        $stmt = $this->connection->prepare("SELECT * FROM user");
        if ($stmt) {
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
            $users = $stmt->fetchAll();
        }
        return $user;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAllPagination(int $limit, int $offset): array
    {
        $users = [];
        $stmt = $this->connection->prepare("SELECT * FROM user LIMIT :limit OFFSET :offset");
        if ($stmt) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
        }
        return $users;
    }

    public function save(object $entity): object
    {
        return new \stdClass();
    }

    public function update(int $id, object $entity): object
    {
        return new \stdClass();
    }

    public function delete(int $id, object $entity): bool
    {
        return true;
    }
}
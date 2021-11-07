<?php

namespace Gouh\BlogApi\App\DAO;

use Gouh\BlogApi\App\Entity\Role;
use PDO;
use PDOException;
use stdClass;

class RoleDao extends AbstractDAO
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
        $role = null;
        $stmt = $this->connection->prepare("SELECT * FROM role WHERE role_id=:id");

        if ($stmt) {
            $stmt->execute(['id' => $id]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, Role::class);
            $role = $stmt->fetch();
        }

        $errors = $stmt->errorInfo();
        if ($errors[1]) {
            throw new PDOException($errors[2], $errors[1]);
        }

        return $role;
    }

    /**
     * @param array $filter
     * @return object[]
     */
    public function findBy(array $filter): array
    {
        $roles = [];
        $sql = "SELECT * FROM role WHERE " . $this->getWhereSql($filter);
        $stmt = $this->connection->prepare($sql);

        if ($stmt) {
            foreach ($filter as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();
            $roles = $stmt->fetchAll(PDO::FETCH_CLASS, Role::class);
        }

        $errors = $stmt->errorInfo();
        if ($errors[1]) {
            throw new PDOException($errors[2], $errors[1]);
        }

        return $roles;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $roles = [];
        $stmt = $this->connection->prepare("SELECT * FROM role");

        if ($stmt) {
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, Role::class);
            $roles = $stmt->fetchAll();
        }

        $errors = $stmt->errorInfo();
        if ($errors[1]) {
            throw new PDOException($errors[2], $errors[1]);
        }

        return $roles;
    }

    /**
     * @param object $entity
     * @return object
     */
    public function save(object $entity): object
    {
        return new stdClass();
    }

    /**
     * @param object $entity
     * @return object
     */
    public function update(object $entity): object
    {
        return new stdClass();
    }

    public function delete(int $id, object $entity): bool
    {
        return false;
    }
}

<?php

namespace Gouh\BlogApi\App\DAO;

use Gouh\BlogApi\App\Entity\User;
use PDO;
use PDOException;
use stdClass;

class UserDAO extends AbstractDAO
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
        $stmt = $this->connection->prepare("SELECT user_id as userId, name, last_name as lastName, email, password, role_id as roleId FROM user WHERE user_id=:id");

        if ($stmt) {
            $stmt->execute(['id' => $id]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
            $user = $stmt->fetch();
        }

        $errors = $stmt->errorInfo();
        if ($errors[1]) {
            throw new PDOException($errors[2], $errors[1]);
        }

        return $user;
    }

    /**
     * @param array $filter
     * @return object[]
     */
    public function findBy(array $filter): array
    {
        $users = [];
        $sql = "SELECT user_id as userId, name, last_name as lastName, email, password, role_id as roleId FROM user WHERE " . $this->getWhereSql($filter);
        $stmt = $this->connection->prepare($sql);

        if ($stmt) {
            foreach ($filter as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
        }

        $errors = $stmt->errorInfo();
        if ($errors[1]) {
            throw new PDOException($errors[2], $errors[1]);
        }

        return $users;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $users = [];
        $stmt = $this->connection->prepare("SELECT user_id as userId, name, last_name as lastName, email, password, role_id as roleId FROM user");

        if ($stmt) {
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
            $users = $stmt->fetchAll();
        }

        $errors = $stmt->errorInfo();
        if ($errors[1]) {
            throw new PDOException($errors[2], $errors[1]);
        }

        return $users;
    }

    /**
     * @param User $entity
     * @return User
     */
    public function save($entity): object
    {
        $stmt = $this->connection->prepare("INSERT INTO user(name, last_name, email, password, role_id) 
                    VALUES(:name, :lastName, :email, :password, :roleId)");

        $user = null;
        if ($stmt) {
            $result = $stmt->execute([
                ':name' => $entity->getName(),
                ':lastName' => $entity->getLastName(),
                ':email' => $entity->getEmail(),
                ':password' => $entity->getPassword(),
                ':roleId' => $entity->getRoleId(),
            ]);
            if ($result) {
                $user = $this->find((int)$this->connection->lastInsertId());
            }
        }

        $errors = $stmt->errorInfo();
        if ($errors[1]) {
            throw new PDOException($errors[2], $errors[1]);
        }

        return $user;
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

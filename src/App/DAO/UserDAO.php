<?php

namespace Gouh\BlogApi\App\DAO;

use Gouh\BlogApi\App\Entity\User;
use PDO;
use PDOException;

class UserDAO extends AbstractDAO implements InterfacePaginationDAO
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
        $sql = "SELECT * FROM user WHERE " . $this->getWhereSql($filter);
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
        $stmt = $this->connection->prepare("SELECT * FROM user");

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

        $errors = $stmt->errorInfo();
        if ($errors[1]) {
            throw new PDOException($errors[2], $errors[1]);
        }

        return $users;
    }

    /**
     * @return object|null
     */
    public function countPagination(): ?object
    {
        $userCount = null;
        $stmt = $this->connection->query("SELECT count(*) FROM user");

        if ($stmt) {
            $stmt->execute();
            $userCount = $stmt->fetch();
        }

        $errors = $stmt->errorInfo();
        if ($errors[1]) {
            throw new PDOException($errors[2], $errors[1]);
        }

        return $userCount;
    }

    /**
     * @param User $entity
     * @return User
     */
    public function save($entity): object
    {
        $stmt = $this->connection->prepare("INSERT INTO user(name, lastName, email, password, rol_id) 
                    VALUES(:name, :lastName, :email, :password, :rolId)");

        $user = null;
        if ($stmt) {
            $result = $stmt->execute([
                ':name' => $entity->getName(),
                ':lastName' => $entity->getLastName(),
                ':email' => $entity->getEmail(),
                ':password' => $entity->getPassword(),
                ':rolId' => $entity->getRolId(),
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
     * @param User $entity
     * @return User
     */
    public function update($entity): object
    {
        $stmt = $this->connection->prepare("UPDATE user SET 
                    name=:name, lastName=:lastName, email=:email, password=:password, rol_id=:rolId
                    WHERE user_id=:userId");

        $user = null;
        if ($stmt) {
            $result = $stmt->execute([
                ':name' => $entity->getName(),
                ':lastName' => $entity->getLastName(),
                ':email' => $entity->getEmail(),
                ':password' => $entity->getPassword(),
                ':rolId' => $entity->getRolId(),
                ':userId' => $entity->getUserId(),
            ]);
            if ($result) {
                $user = $entity;
            }
        }

        $errors = $stmt->errorInfo();
        if ($errors[1]) {
            throw new PDOException($errors[2], $errors[1]);
        }

        return $user;
    }

    public function delete(int $id, object $entity): bool
    {
        return false;
    }
}

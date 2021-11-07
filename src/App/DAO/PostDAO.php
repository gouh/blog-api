<?php

namespace Gouh\BlogApi\App\DAO;

use Gouh\BlogApi\App\Entity\Post;
use PDO;
use PDOException;

class PostDAO extends AbstractDAO implements InterfacePaginationDAO
{

    /** @var PDO */
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function find(int $id): ?object
    {
        $user = null;
        $stmt = $this->connection->prepare("SELECT 
                post_id as postId, 
                user_id as userId,
                title, 
                description, 
                created_at as createdAt 
            FROM post WHERE user_id=:id and status=1");

        if ($stmt) {
            $stmt->execute(['id' => $id]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, Post::class);
            $user = $stmt->fetch();
        }

        $errors = $stmt->errorInfo();
        if ($errors[1]) {
            throw new PDOException($errors[2], $errors[1]);
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
        $posts = [];
        $stmt = $this->connection->prepare("SELECT 
                post.post_id as postId,
                post.title,
                post.description,
                post.created_at as createdAt,
                user.user_id as userId,
                user.name as userName,
                role.name as userRole
            FROM post 
            JOIN user on post.user_id = user.user_id
            JOIN role on user.role_id = role.role_id
            WHERE status=1
            LIMIT :limit OFFSET :offset");

        if ($stmt) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $posts = $stmt->fetchAll();
        }

        $errors = $stmt->errorInfo();
        if ($errors[1]) {
            throw new PDOException($errors[2], $errors[1]);
        }

        return $posts;
    }

    /**
     * @return object|null
     */
    public function countPagination(): ?array
    {
        $userCount = null;
        $stmt = $this->connection->query("SELECT count(*) as postCount FROM post where status=1");

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
     * @param Post $entity
     * @return object
     */
    public function save($entity): object
    {
        $stmt = $this->connection->prepare("INSERT INTO post(title, description, created_at, user_id) 
                    VALUES(:title, :description, :createdAt, :userId)");

        $user = null;
        if ($stmt) {
            $result = $stmt->execute([
                ':userId' => $entity->getUserId(),
                ':title' => $entity->getTitle(),
                ':description' => $entity->getDescription(),
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

    public function update(object $entity): object
    {
        $stmt = $this->connection->prepare("UPDATE post SET 
                    title=:title, description=:description
                    WHERE post_id=:postId");

        $user = null;
        if ($stmt) {
            $result = $stmt->execute([
                ':title' => $entity->getName(),
                ':description' => $entity->getLastName(),
                ':postId' => $entity->getLastName(),
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

    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare("UPDATE post SET status=0 WHERE post_id=:postId");

        $deleted = false;
        if ($stmt) {
            $result = $stmt->execute([':postId' => $id]);
            $deleted = $result;
        }

        $errors = $stmt->errorInfo();
        if ($errors[1]) {
            throw new PDOException($errors[2], $errors[1]);
        }

        return $deleted;
    }

    public function findBy(array $filter): array
    {
        return [];
    }

    public function findAll(): array
    {
        return [];
    }
}

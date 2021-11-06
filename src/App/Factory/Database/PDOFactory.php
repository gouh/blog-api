<?php

namespace Gouh\BlogApi\App\Factory\Database;

use PDO;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class PDOFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): PDO
    {
        $config = $container->get('config');
        $dsn = $config['database']['dsn'];
        $params = $config['database']['params'];
        return new PDO($dsn, $params['username'], $params['password']);
    }
}

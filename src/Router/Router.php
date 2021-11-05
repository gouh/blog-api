<?php

namespace Gouh\BlogApi\Router;

use ArrayIterator;
use ArrayObject;
use Exception;

final class Router
{
    private const NO_ROUTE = 404;

    /**
     * @var ArrayObject<Route>
     */
    private $routes;

    /**
     * Router constructor.
     * @param $routes array<Route>
     */
    public function __construct(array $routes = [])
    {
        $this->routes = new ArrayIterator();
        foreach ($routes as $route) {
            $this->add($route);
        }
    }

    /**
     * @param Route $route
     * @return $this
     */
    public function add(Route $route): self
    {
        $this->routes->offsetSet($route->getName(), $route);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function matchFromPath(string $path, string $method): Route
    {
        foreach ($this->routes as $route) {
            if ($route->match($path, $method) === false) {
                continue;
            }
            return $route;
        }

        throw new Exception(
            'No route found for ' . $method,
            self::NO_ROUTE
        );
    }
}

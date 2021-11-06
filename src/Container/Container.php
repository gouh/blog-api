<?php

namespace Gouh\BlogApi\Container;

use Closure;
use Gouh\BlogApi\Container\Exceptions\ContainerException;
use Gouh\BlogApi\Container\Exceptions\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * Class Container
 */
class Container implements ContainerInterface
{
    /** @var array */
    protected array $entries = [];

    /** @var array */
    protected array $instances = [];

    /** @var array */
    protected array $rules = [];

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            if (isset($config['factory'])) {
                foreach ($config['factory'] as $id => $concrete) {
                    $this->set($id, $concrete);
                }
                unset($config['factory']);
                $this->configure($config);
            }
            if (isset($config['config'])) {
                $this->set('config', $config['config']);
            }
        }
    }

    /**
     * @param string $id Identifier of the entry to look for.
     * @return mixed Entry.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     * @throws NotFoundExceptionInterface|ReflectionException  No entry was found for **this** identifier.
     */
    public function get(string $id)
    {
        if (!$this->has($id)) {
            $this->set($id);
        }
        if (is_array($this->entries[$id])) {
            return $this->entries[$id];
        }
        if ($this->entries[$id] instanceof Closure || is_callable($this->entries[$id])) {
            return $this->entries[$id]($this);
        }
        if (isset($this->rules['shared']) && in_array($id, $this->rules['shared'])) {
            return $this->singleton($id);
        }

        $resolveClass = $this->resolve($id);
        if (is_callable($resolveClass)) {
            return $resolveClass($this);
        }

        return $resolveClass;
    }

    /**
     * @param string $id Identifier of the entry to look for.
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    /**
     * @param $abstract
     * @param null $concrete
     */
    public function set($abstract, $concrete = null)
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }
        $this->entries[$abstract] = $concrete;
    }

    /**
     * Resolves a class name and creates its instance with dependencies
     * @param $id
     * @return object The resolved instance
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function resolve($id): object
    {
        $reflector = $this->getReflector($id);
        $constructor = $reflector->getConstructor();
        if ($reflector->isInterface()) {
            return $this->resolveInterface($reflector);
        }
        if (!$reflector->isInstantiable()) {
            throw new ContainerException(
                "Cannot inject {$reflector->getName()} to $id because it cannot be instantiated"
            );
        }
        if (null === $constructor) {
            return $reflector->newInstance();
        }
        $args = $this->getArguments($id, $constructor);
        return $reflector->newInstanceArgs($args);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function singleton($id)
    {
        if (!isset($this->instances[$id])) {
            $this->instances[$id] = $this->resolve(
                $this->entries[$id]
            );
        }
        return $this->instances[$id];
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function getReflector($id): ReflectionClass
    {
        $class = $this->entries[$id];
        try {
            return (new ReflectionClass($class));
        } catch (ReflectionException $e) {
            throw new NotFoundException(
                $e->getMessage(), $e->getCode()
            );
        }
    }

    /**
     * Get the constructor arguments of a class
     * @param ReflectionMethod $constructor The constructor
     * @return array The arguments
     * @throws NotFoundException|ContainerExceptionInterface|ReflectionException
     */
    public function getArguments($id, ReflectionMethod $constructor): array
    {
        $args = [];
        $params = $constructor->getParameters();
        foreach ($params as $param) {
            if (null !== $param->getClass()) {
                $args[] = $this->get(
                    $param->getClass()->getName()
                );
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } elseif (isset($this->rules[$id][$param->getName()])) {
                $args[] = $this->rules[$id][$param->getName()];
            }
        }
        return $args;
    }

    /**
     * Returns instance implementing the type hinted interface
     * @param ReflectionClass $reflector The interface Reflector
     * @return object Instance implementing the interface
     * @throws ReflectionException
     * @throws NotFoundException|ContainerExceptionInterface
     */
    public function resolveInterface(ReflectionClass $reflector): object
    {
        if (isset($this->rules['substitute'][$reflector->getName()])) {
            return $this->get(
                $this->rules['substitute'][$reflector->getName()]
            );
        }

        $classes = get_declared_classes();
        foreach ($classes as $class) {
            $rf = new ReflectionClass($class);
            if ($rf->implementsInterface($reflector->getName())) {
                return $this->get($rf->getName());
            }
        }
        throw new NotFoundException(
            "Class {$reflector->getName()} not found", 1
        );
    }

    /**
     * @param array $config
     * @return $this
     */
    public function configure(array $config): Container
    {
        $this->rules = array_merge($this->rules, $config);
        return $this;
    }
}

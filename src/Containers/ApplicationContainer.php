<?php

namespace App\Containers;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

class ApplicationContainer {
    /**
     * @var array
     */
    protected array $bindings = [];

    /**
     * @param $abstract
     * @param $concrete
     * @return void
     */
    public function bind($abstract, $concrete = null): void
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }
        $this->bindings[$abstract] = $concrete instanceof Closure ? $concrete : $this->getClosure($abstract, $concrete);
    }

    /**
     * @throws Exception
     */
    public function make($abstract)
    {
        return $this->resolve($abstract);
    }

    /**
     * @param $abstract
     * @param $concrete
     * @return Closure
     */
    protected function getClosure($abstract, $concrete): Closure
    {
        return function($container) use ($abstract, $concrete) {
            if ($abstract == $concrete) {
                return $container->build($concrete);
            }
            return $container->make($concrete);
        };
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    protected function build($concrete)
    {
        $reflector = new ReflectionClass($concrete);
        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$concrete} is not instantiable");
        }
        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return new $concrete;
        }
        $parameters = $constructor->getParameters();
        $dependencies = array_map(function($parameter) {
            $type = $parameter->getType();
            if (!$type) {
                throw new Exception("Cannot resolve the dependency {$parameter->name} because it has no declared type");
            }
            $typeName = $type instanceof ReflectionNamedType ? $type->getName() : (string)$type;
            if (!$type->isBuiltin()) {
                return $this->resolve($typeName);
            }
            throw new Exception("Cannot resolve the built-in type: {$typeName}");
        }, $parameters);
        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * @throws Exception
     */
    protected function resolve($name)
    {
        if (isset($this->bindings[$name])) {
            $concrete = $this->bindings[$name];
            return $concrete($this);
        }
        if (class_exists($name)) {
            return $this->build($name);
        }
        throw new Exception("Class {$name} does not exist");
    }
}

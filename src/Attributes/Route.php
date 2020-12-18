<?php

namespace Spatie\RouteAttributes\Attributes;

use Attribute;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use ReflectionClass;
use ReflectionMethod;
use Spatie\RouteAttributes\Attributes\Contracts\MethodAttributeRouteCreate;

#[Attribute(Attribute::TARGET_METHOD|Attribute::IS_REPEATABLE)]
class Route implements MethodAttributeRouteCreate
{
    public array $middleware;

    public function __construct(
        public string $method,
        public string $uri,
        public ?string $name = null,
        array|string $middleware = [],
    ) {
        $this->middleware = Arr::wrap($middleware);
    }

    public function runMethodAttributeRouteCreate(
        ReflectionClass $class,
        ReflectionMethod $method,
        Router $router,
    ): \Illuminate\Routing\Route {
        $action = $method->getName() === '__invoke'
            ? $class->getName()
            : [$class->getName(), $method->getName()];

        /** @var \Illuminate\Routing\Route $route */
        $route = $router->{$this->method}($this->uri, $action);

        $route->name($this->name);
        $route->middleware($this->middleware);

        return $route;
    }
}

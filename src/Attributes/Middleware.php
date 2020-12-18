<?php

namespace Spatie\RouteAttributes\Attributes;

use Attribute;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Arr;
use ReflectionClass;
use ReflectionMethod;
use Spatie\RouteAttributes\Attributes\Contracts\RouteClassGroupAttribute;
use Spatie\RouteAttributes\Attributes\Contracts\MethodAttributeRouteUpdate;

#[Attribute(Attribute::TARGET_CLASS|Attribute::TARGET_METHOD|Attribute::IS_REPEATABLE)]
class Middleware implements RouteClassGroupAttribute, MethodAttributeRouteUpdate
{
    public array $middleware = [];

    public function __construct(string|array $middleware = [])
    {
        $this->middleware = Arr::wrap($middleware);
    }

    public function runRouteClassGroupAttribute(ReflectionClass $class, Router $router, ?RouteRegistrar $route): RouteRegistrar
    {
        return $route?->middleware($this->middleware) ?? $router->middleware($this->middleware);
    }

    public function runMethodAttributeRouteUpdate(
        ReflectionClass $class,
        ReflectionMethod $method,
        Router $router,
        Route $route
    ): Route {
        return $route?->middleware($this->middleware);
    }
}

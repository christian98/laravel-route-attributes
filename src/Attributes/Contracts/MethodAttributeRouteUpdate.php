<?php


namespace Spatie\RouteAttributes\Attributes\Contracts;


use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use ReflectionClass;
use ReflectionMethod;
use Spatie\RouteAttributes\Attributes\RouteAttribute;

interface MethodAttributeRouteUpdate extends RouteAttribute
{
    public function runMethodAttributeRouteUpdate(
        ReflectionClass $class,
        ReflectionMethod $method,
        Router $router,
        Route $route
    ): ?Route;
}

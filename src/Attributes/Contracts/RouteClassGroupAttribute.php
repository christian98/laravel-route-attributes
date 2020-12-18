<?php


namespace Spatie\RouteAttributes\Attributes\Contracts;

use Illuminate\Routing\Router;
use Illuminate\Routing\RouteRegistrar;
use ReflectionClass;
use Spatie\RouteAttributes\Attributes\RouteAttribute;

interface RouteClassGroupAttribute extends RouteAttribute
{
    public function runRouteClassGroupAttribute(
        ReflectionClass $class,
        Router $router,
        ?RouteRegistrar $route
    ): RouteRegistrar;
}

<?php


namespace Spatie\RouteAttributes\Attributes\Contracts;


use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use ReflectionClass;
use Spatie\RouteAttributes\Attributes\RouteAttribute;

interface RouteClassAttribute extends RouteAttribute
{
    public function runRouteClassAttribute(ReflectionClass $class, Router $router);
}

<?php


namespace Spatie\RouteAttributes\Attributes\Contracts;


use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use ReflectionClass;
use ReflectionMethod;
use Spatie\RouteAttributes\Attributes\RouteAttribute;

interface MethodAttributeRouteCreate extends RouteAttribute
{
    public function runMethodAttributeRouteCreate(ReflectionClass $class, ReflectionMethod $method, Router $router): Route;
}

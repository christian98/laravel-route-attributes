<?php

namespace Spatie\RouteAttributes\Attributes;

use Attribute;
use Illuminate\Routing\Router;
use Illuminate\Routing\RouteRegistrar;
use ReflectionClass;
use Spatie\RouteAttributes\Attributes\Contracts\RouteClassGroupAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Prefix implements RouteClassGroupAttribute
{
    public function __construct(
        public $prefix
    ) {}

    public function runRouteClassGroupAttribute(
        ReflectionClass $class,
        Router $router,
        ?RouteRegistrar $route
    ): RouteRegistrar {
        // TODO: Implement executeClassAttribute() method.
        return $route?->prefix($this->prefix) ?? \Illuminate\Support\Facades\Route::prefix($this->prefix);
    }
}

<?php


namespace Spatie\RouteAttributes\Attributes;

use Attribute;
use Illuminate\Routing\Router;
use ReflectionClass;
use Spatie\RouteAttributes\Attributes\Contracts\RouteClassAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ResourceController implements RouteClassAttribute
{
    public function __construct(
        public string $name,
        public array $options = [],
    ) {}

    public function runRouteClassAttribute(ReflectionClass $class, Router $router)
    {
        $router->resource($this->name, $class->getName(), $this->options);
    }
}

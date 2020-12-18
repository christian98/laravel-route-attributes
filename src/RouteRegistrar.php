<?php

namespace Spatie\RouteAttributes;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Routing\RouteRegistrar as LaravelRouteRegistrar;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionAttribute;
use ReflectionClass;
use Spatie\RouteAttributes\Attributes\Contracts\MethodAttributeRouteCreate;
use Spatie\RouteAttributes\Attributes\Contracts\RouteClassAttribute;
use Spatie\RouteAttributes\Attributes\Contracts\RouteClassGroupAttribute;
use Spatie\RouteAttributes\Attributes\Contracts\MethodAttributeRouteUpdate;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use Throwable;

class RouteRegistrar
{
    private Router $router;

    protected string $basePath;

    private string $rootNamespace;

    public function __construct(Router $router)
    {
        $this->router = $router;

        $this->basePath = app()->path();
    }

    public function useBasePath(string $basePath): self
    {
        $this->basePath = $basePath;

        return $this;
    }

    public function useRootNamespace(string $rootNamespace): self
    {
        $this->rootNamespace = $rootNamespace;

        return $this;
    }

    public function registerDirectory(string|array $directories): void
    {
        $directories = Arr::wrap($directories);

        $files = (new Finder())->files()->name('*.php')->in($directories);

        collect($files)->each(fn(SplFileInfo $file) => $this->registerFile($file));
    }

    public function registerFile(string|SplFileInfo $path): void
    {
        if (is_string($path)) {
            $path = new SplFileInfo($path);
        }

        $fullyQualifiedClassName = $this->fullQualifiedClassNameFromFile($path);

        $this->processAttributes($fullyQualifiedClassName);
    }

    public function registerClass(string $class)
    {
        $this->processAttributes($class);
    }

    protected function fullQualifiedClassNameFromFile(SplFileInfo $file): string
    {
        $class = trim(Str::replaceFirst($this->basePath, '', $file->getRealPath()), DIRECTORY_SEPARATOR);

        $class = str_replace(
            [DIRECTORY_SEPARATOR, 'App\\'],
            ['\\', app()->getNamespace()],
            ucfirst(Str::replaceLast('.php', '', $class))
        );

        return $this->rootNamespace.$class;
    }

    protected function processAttributes(string $className): void
    {
        if (!class_exists($className)) {
            return;
        }

        $class = new ReflectionClass($className);

        $classAttributes = $class->getAttributes(RouteClassGroupAttribute::class, ReflectionAttribute::IS_INSTANCEOF);

        if(!count($classAttributes)) {
            // no group
            $this->processAttributesForMethods($class);
        } else {
            // group
            /** @var LaravelRouteRegistrar|null $classRouteRegistrar */
            $classRouteRegistrar = null;
            foreach ($classAttributes as $classAttribute) {
                try {
                    /** @var RouteClassGroupAttribute $classAttributeInstance */
                    $classAttributeInstance = $classAttribute->newInstance();
                    $classRouteRegistrar = $classAttributeInstance->runRouteClassGroupAttribute($class, $this->router, $classRouteRegistrar);
                } catch(Throwable) {
                    continue;
                }
            }
            $classRouteRegistrar->group(function() use ($class) {
                $this->processAttributesForMethods($class);
            });
        }

        foreach ($class->getAttributes(RouteClassAttribute::class, ReflectionAttribute::IS_INSTANCEOF) as $classAttribute) {
            /** @var RouteClassAttribute $classAttributeInstance */
            $classAttributeInstance = $classAttribute->newInstance();
            $classAttributeInstance->runRouteClassAttribute($class, $this->router);
        }
    }

    private function processAttributesForMethods(ReflectionClass $class)
    {
        foreach ($class->getMethods() as $method) {
            /** @var Route[] $routes */
            $routes = [];

            $createAttributes = $method->getAttributes(MethodAttributeRouteCreate::class, ReflectionAttribute::IS_INSTANCEOF);
            foreach ($createAttributes as $createAttribute){
                try {
                    /** @var MethodAttributeRouteCreate $attributeClass */
                    $attributeClass = $createAttribute->newInstance();
                    $routes[] = $attributeClass->runMethodAttributeRouteCreate($class, $method, $this->router);
                } catch (Throwable) {
                    continue;
                }
            }

            $updateAttributes = $method->getAttributes(MethodAttributeRouteUpdate::class, ReflectionAttribute::IS_INSTANCEOF);
            foreach ($routes as $route) {
                foreach ($updateAttributes as $updateAttribute) {
                    try {
                        /** @var MethodAttributeRouteUpdate $attributeClass */
                        $attributeClass = $updateAttribute->newInstance();
                        $route = $attributeClass->runMethodAttributeRouteUpdate($class, $method, $this->router, $route) ?? $route;
                    } catch (Throwable) {
                        continue;
                    }
                }
            }
        }
    }
}

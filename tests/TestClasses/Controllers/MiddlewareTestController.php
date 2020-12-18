<?php

namespace Spatie\RouteAttributes\Tests\TestClasses\Controllers;

use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use Spatie\RouteAttributes\Tests\TestClasses\Middleware\OtherTestMiddleware;
use Spatie\RouteAttributes\Tests\TestClasses\Middleware\ThirdTestMiddleware;
use Spatie\RouteAttributes\Tests\TestClasses\Middleware\TestMiddleware;

#[Middleware(TestMiddleware::class)]
class MiddlewareTestController
{
    #[Route('get', 'single-middleware')]
    public function singleMiddleware()
    {
    }

    #[Route('get', 'multiple-middleware', middleware: OtherTestMiddleware::class)]
    public function multipleMiddleware()
    {
    }

    #[Route('get', 'multiple-middleware-2')]
    #[Middleware(OtherTestMiddleware::class)]
    public function multipleMiddleware2()
    {
    }

    #[Route('get', 'multiple-middleware-3', middleware: OtherTestMiddleware::class)]
    #[Middleware(ThirdTestMiddleware::class)]
    public function multipleMiddleware3()
    {
    }
}

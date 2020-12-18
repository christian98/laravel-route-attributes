<?php


namespace Spatie\RouteAttributes\Tests\TestClasses\Controllers;

use Spatie\RouteAttributes\Attributes\ResourceController;

#[ResourceController('test')]
class ResourceTestController
{
    public function index()
    {
    }
}

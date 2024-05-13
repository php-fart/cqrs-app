<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use Spiral\Bootloader\Http\RoutesBootloader as BaseRoutesBootloader;
use Spiral\Debug\StateCollector\HttpCollector;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;
use Spiral\Http\Middleware\JsonPayloadMiddleware;
use Spiral\Nyholm\Bootloader\NyholmBootloader;
use Spiral\Router\Bootloader\AnnotatedRoutesBootloader;
use Spiral\Router\Loader\Configurator\RoutingConfigurator;
use Spiral\RoadRunnerBridge\Bootloader as RoadRunnerBridge;
use Spiral\Bootloader as Framework;

final class RoutesBootloader extends BaseRoutesBootloader
{
    public function defineDependencies(): array
    {
        return [
            RoadRunnerBridge\HttpBootloader::class,
            Framework\Http\RouterBootloader::class,
            Framework\Http\JsonPayloadsBootloader::class,
            NyholmBootloader::class,
            AnnotatedRoutesBootloader::class,
        ];
    }

    protected function globalMiddleware(): array
    {
        return [
            ErrorHandlerMiddleware::class,
            JsonPayloadMiddleware::class,
            HttpCollector::class,
        ];
    }

    protected function middlewareGroups(): array
    {
        return [
            'web' => [
            ],
        ];
    }
}

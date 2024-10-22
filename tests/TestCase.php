<?php

declare(strict_types=1);

namespace Tests;

use Spiral\Config\ConfiguratorInterface;
use Spiral\Config\Patch\Set;
use Spiral\Core\Container;
use Spiral\Testing\TestableKernelInterface;
use Spiral\Testing\TestCase as BaseTestCase;
use Spiral\Translator\TranslatorInterface;
use Tests\App\TestKernel;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function createAppInstance(Container $container = new Container()): TestableKernelInterface
    {
        return TestKernel::create(
            directories: $this->defineDirectories(
                $this->rootDirectory(),
            ),
            container: $container,
        );
    }

    protected function tearDown(): void
    {
        // Uncomment this line if you want to clean up runtime directory.
        // $this->cleanUpRuntimeDirectory();
    }

    public function rootDirectory(): string
    {
        return __DIR__ . '/..';
    }

    public function defineDirectories(string $root): array
    {
        return [
            'root' => $root,
        ];
    }
}

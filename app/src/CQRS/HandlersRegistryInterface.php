<?php

declare(strict_types=1);

namespace App\CQRS;

interface HandlersRegistryInterface
{
    /**
     * @param class-string<CommandInterface> $commandClass
     */
    public function register(string $commandClass, callable $handler): void;
}

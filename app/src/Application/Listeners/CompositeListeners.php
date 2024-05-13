<?php

declare(strict_types=1);

namespace App\Application\Listeners;

use App\CQRS\CommandInterface;
use App\CQRS\CommandListenerInterface;
use App\CQRS\OperationInterface;

final readonly class CompositeListeners implements CommandListenerInterface
{
    public function __construct(
        private array $listeners,
    ) {}

    public function listen(CommandInterface $command, OperationInterface $operation): void
    {
        foreach ($this->listeners as $listener) {
            $listener->listen($command, $operation);
        }
    }

    public function fail(\Throwable $e): void
    {
        foreach ($this->listeners as $listener) {
            $listener->fail($e);
        }
    }

    public function flush(): void
    {
        foreach ($this->listeners as $listener) {
            $listener->flush();
        }
    }
}

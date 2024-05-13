<?php

declare(strict_types=1);

namespace App\Application\Listeners;

use App\CQRS\CommandInterface;
use App\CQRS\CommandListenerInterface;
use App\CQRS\OperationInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

final class UserRegisterEventsListener implements CommandListenerInterface
{
    private array $events = [];

    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
        private readonly CommandEventsRegistry $registry,
    ) {}

    public function listen(CommandInterface $command, OperationInterface $operation): void
    {
        if ($this->registry->has($command)) {
            $this->events[] = $this->registry->make($command);
        }
    }

    public function fail(\Throwable $e): void
    {
        $this->events = [];
    }

    public function flush(): void
    {
        foreach ($this->events as $event) {
            $this->dispatcher->dispatch($event);
        }
    }
}

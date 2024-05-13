<?php

declare(strict_types=1);

namespace App\CQRS;

use App\CQRS\Stamps\OperationStamp;
use Spiral\Prototype\Annotation\Prototyped;
use Symfony\Component\Messenger\MessageBusInterface;

#[Prototyped('commandBus')]
final readonly class CommandBus implements CommandBusInterface
{
    public function __construct(
        private MessageBusInterface $bus,
        private CommandListenerInterface $listener,
    ) {}

    public function dispatch(CommandInterface $command): OperationInterface
    {
        $operation = Operation::create();

        $this->bus->dispatch($command, [
            new OperationStamp(operation: $operation),
        ]);

        return $operation;
    }

    public function dispatchMany(CommandInterface ...$commands): array
    {
        $operations = [];

        foreach ($commands as $command) {
            try {
                $operation = $this->dispatch($command);
                $operations[] = $operation;
                $this->listener->listen($command, $operation);
            } catch (\Throwable $e) {
                $this->listener->fail($e);
                throw $e;
            }
        }

        $this->listener->flush();

        return $operations;
    }
}

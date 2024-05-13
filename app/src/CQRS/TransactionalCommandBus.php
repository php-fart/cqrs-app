<?php

declare(strict_types=1);

namespace App\CQRS;

final readonly class TransactionalCommandBus implements CommandBusInterface
{
    public function __construct(
        private CommandBusInterface $bus,
        private TransactionInterface $transaction,
    ) {}

    public function dispatch(CommandInterface $command): OperationInterface
    {
        return $this->bus->dispatch($command);
    }

    public function dispatchMany(CommandInterface ...$commands): array
    {
        $this->transaction->begin();

        try {
            $operations = $this->bus->dispatchMany(...$commands);
            $this->transaction->commit();

            return $operations;
        } catch (\Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}

<?php

declare(strict_types=1);

namespace App\CQRS;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): OperationInterface;

    /**
     * @param CommandInterface ...$commands
     * @return array<OperationInterface>
     */
    public function dispatchMany(CommandInterface ... $commands): array;
}

<?php

declare(strict_types=1);

namespace App\CQRS;

interface CommandListenerInterface
{
    public function listen(CommandInterface $command, OperationInterface $operation): void;

    public function fail(\Throwable $e): void;

    public function flush(): void;
}

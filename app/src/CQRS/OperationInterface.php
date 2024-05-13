<?php

declare(strict_types=1);

namespace App\CQRS;

use Ramsey\Uuid\UuidInterface;

interface OperationInterface
{
    public function getUuid(): UuidInterface;
}

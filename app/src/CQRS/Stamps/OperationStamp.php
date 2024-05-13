<?php

declare(strict_types=1);

namespace App\CQRS\Stamps;

use App\CQRS\OperationInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

final readonly class OperationStamp implements StampInterface
{
    public function __construct(
        public OperationInterface $operation,
    ) {}
}

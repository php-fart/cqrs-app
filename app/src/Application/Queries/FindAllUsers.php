<?php

declare(strict_types=1);

namespace App\Application\Queries;

use App\CQRS\QueryInterface;

final readonly class FindAllUsers implements QueryInterface
{
    public function __construct(
        public bool $isActive = true,
    ) {}
}

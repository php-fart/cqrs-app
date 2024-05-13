<?php

declare(strict_types=1);

namespace App\Application\Queries\Dto;

final readonly class User
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public bool $isActive,
    ) {}
}

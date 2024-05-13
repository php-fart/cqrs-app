<?php

declare(strict_types=1);

namespace App\Application\Commands;

use App\CQRS\Attributes\Async;
use App\CQRS\CommandInterface;

#[Async]
final readonly class CreateUser implements CommandInterface
{
    public function __construct(
        public string $username,
        public string $email,
        public string $password,
    ) {}
}

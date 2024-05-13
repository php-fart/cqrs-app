<?php

declare(strict_types=1);

namespace App\Modules\User\Entity;

// Database entity
class User
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $password,
        public bool $isActive = false,
    ) {}
}

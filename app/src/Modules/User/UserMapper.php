<?php

declare(strict_types=1);

namespace App\Modules\User;

use App\Application\Queries\Dto\User;

final class UserMapper
{
    public function toDto(\App\Modules\User\Entity\User $user): User
    {
        return new User(
            $user->id,
            $user->name,
            $user->email,
            $user->isActive,
        );
    }
}

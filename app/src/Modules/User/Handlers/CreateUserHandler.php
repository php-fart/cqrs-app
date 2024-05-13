<?php

declare(strict_types=1);

namespace App\Modules\User\Handlers;

use App\Application\Commands\CreateUser;
use App\CQRS\Attributes\CommandHandler;

final readonly class CreateUserHandler
{
    #[CommandHandler]
    public function __invoke(CreateUser $command): void
    {
        $user = new User(
            $command->username,
            $command->email,
            $command->password
        );

        $this->em->persist($user);
    }
}

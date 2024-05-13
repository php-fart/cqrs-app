<?php

declare(strict_types=1);

namespace App\Modules\User\Handlers;

use App\Application\Commands\CreateUser;
use App\CQRS\Attributes\CommandHandler;
use App\CQRS\CommandBusInterface;

final readonly class RegisterNewUserHandler
{
    public function __construct(
        private CommandBusInterface $bus,
    ) {}

    #[CommandHandler]
    public function __invoke(RegisterUser $command): void
    {
        $this->bus->dispatchMany(
            new CreateUser($command->username, $command->email, $command->password),
            new GenerateVerificationToken($user->email),
            new GenerageAuthToken($user->uuid),
        );
    }

}

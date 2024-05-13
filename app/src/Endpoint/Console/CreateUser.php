<?php

declare(strict_types=1);

namespace App\Endpoint\Console;

use App\CQRS\CommandBusInterface;
use Spiral\Console\Attribute\Argument;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Command;

#[AsCommand(
    name: 'create:user',
    description: 'Create a new user'
)]
final class CreateUser extends Command
{
    #[Argument(name: 'username', description: 'The username of the user')]
    public string $username;

    #[Argument(name: 'email', description: 'The email of the user')]
    public string $email;

    public function __invoke(CommandBusInterface $bus): int
    {
        $bus->dispatch(
            new \App\Application\Commands\CreateUser(
                username: $this->username,
                email: $this->email,
                password: 'password',
            ),
        );

        $this->writeln('User created successfully');

        return 0;
    }
}

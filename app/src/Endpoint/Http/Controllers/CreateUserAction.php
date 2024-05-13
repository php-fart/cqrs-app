<?php

declare(strict_types=1);

namespace App\Endpoint\Http\Controllers;

use App\Application\Commands\CreateUser;
use App\CQRS\CommandBusInterface;
use Spiral\Router\Annotation\Route;

final readonly class CreateUserAction
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {}

    #[Route(route: '/users/create', name: 'users.create', methods: ['GET'])]
    public function __invoke(): string
    {
        $operation = $this->commandBus->dispatch(
            new CreateUser(
                username: 'John Doe',
                email: 'john_doe@site.com',
                password: 'password',
            ),
        );

        return (string) $operation->getUuid();
    }
}

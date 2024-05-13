<?php

declare(strict_types=1);

namespace App\Endpoint\Http\Controllers;

use App\Application\Queries\FindAllUsers;
use App\CQRS\QueryBusInterface;
use Spiral\Router\Annotation\Route;

final readonly class FindUsersAction
{
    #[Route(route: 'users', name: 'users.all', methods: ['GET'])]
    public function __invoke(QueryBusInterface $bus): array
    {
        $users = $bus->ask(
            new FindAllUsers(
                isActive: true,
            ),
        );

        return \iterator_to_array($users);
    }
}

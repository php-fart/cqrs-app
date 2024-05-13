<?php

declare(strict_types=1);

namespace App\Modules\User\Query;

use App\Application\Queries\Dto\User;
use App\Application\Queries\FindAllUsers;
use App\CQRS\Attributes\QueryHandler;
use App\Modules\User\UserMapper;

final readonly class FindAllUsersHandler
{
    public function __construct(
        private UserRepository $repository,
        private UserMapper $mapper,
    ) {}

    /**
     * @param FindAllUsers $query
     * @return iterable<User>
     */
    #[QueryHandler]
    public function __invoke(FindAllUsers $query): iterable
    {
        /** @var \App\Modules\User\Entity\User[] $users */
        $users = $this->repository->findAll();

        foreach ($users as $user) {
            yield $this->mapper->toDto($user);
        }
    }
}

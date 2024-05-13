<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Application\Commands\CreateUser;
use App\Application\Queries\FindAllUsers;
use App\CQRS\CommandBusInterface;
use App\CQRS\QueryBusInterface;
use Ramsey\Uuid\UuidInterface;
use Tests\TestCase;

final class QueryBusTest extends TestCase
{
    public function testDispatchCommand(): void
    {
        /** @var QueryBusInterface $bus */
        $bus = $this->getContainer()->get(QueryBusInterface::class);

        $user = $bus->ask(
            new FindAllUsers(
                isActive: true
            )
        );

        $this->assertSame([], \iterator_to_array($user));
    }
}

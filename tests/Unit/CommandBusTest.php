<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Application\Commands\CreateUser;
use App\CQRS\CommandBusInterface;
use Ramsey\Uuid\UuidInterface;
use Tests\TestCase;

final class CommandBusTest extends TestCase
{
    public function testDispatchCommand(): void
    {
        /** @var CommandBusInterface $bus */
        $bus = $this->getContainer()->get(CommandBusInterface::class);

        $operation = $bus->dispatch(
            new CreateUser(
                username: 'john_doe',
                email: 'john_doe@site.com',
                password: 'password',
            ),
        );

        $this->assertInstanceOf(UuidInterface::class, $operation->getUuid());
    }
}

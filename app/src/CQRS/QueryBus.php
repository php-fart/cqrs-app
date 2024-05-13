<?php

declare(strict_types=1);

namespace App\CQRS;

use Spiral\Prototype\Annotation\Prototyped;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[Prototyped('queryBus')]
final readonly class QueryBus implements QueryBusInterface
{
    public function __construct(
        private MessageBusInterface $bus,
    ) {}

    public function ask(QueryInterface $query): mixed
    {
        $envelope = $this->bus->dispatch($query);

        $stamp = $envelope->last(HandledStamp::class);
        if ($stamp === null) {
            throw new \RuntimeException('Query handler did not return a result');
        }

        return $stamp->getResult();
    }
}

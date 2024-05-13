<?php

declare(strict_types=1);

namespace App\CQRS\Middlewares;

use App\CQRS\Attributes\Async;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

final class AsyncCommandMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $command = $envelope->getMessage();
        // find async attribute
        $refl = new \ReflectionClass($command);
        $attr = $refl->getAttributes(Async::class)[0] ?? null;
        if ($attr !== null) {
            $envelope = $envelope->with(
                new TransportNamesStamp(['async']),
            );
        }

        return $stack->next()->handle($envelope, $stack);
    }
}

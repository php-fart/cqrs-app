<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Application\Listeners\CompositeListeners;
use App\Application\Listeners\UserRegisterEventsListener;
use App\CQRS\AttributesHandlersLocator;
use App\CQRS\CommandBus;
use App\CQRS\CommandBusInterface;
use App\CQRS\HandlersRegistryInterface;
use App\CQRS\Middlewares\AsyncCommandMiddleware;
use App\CQRS\QueryBus;
use App\CQRS\QueryBusInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Tokenizer\Bootloader\TokenizerListenerBootloader;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final class CqrsBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            HandlersLocatorInterface::class => AttributesHandlersLocator::class,
            HandlersRegistryInterface::class => AttributesHandlersLocator::class,

            CommandBusInterface::class => static fn(
                HandlersLocatorInterface $handlersLocator,
                EventDispatcherInterface $events,
            ): CommandBusInterface => new CommandBus(
                bus: new MessageBus([
                    new AsyncCommandMiddleware(),
                    new HandleMessageMiddleware(handlersLocator: $handlersLocator),
                ]),
                listener: new CompositeListeners(
                    listeners: [
                        new UserRegisterEventsListener($events),
                    ],
                ),
            ),

            QueryBusInterface::class => static fn(
                HandlersLocatorInterface $handlersLocator,
            ): QueryBusInterface => new QueryBus(
                bus: new MessageBus([
                    new HandleMessageMiddleware(
                        handlersLocator: $handlersLocator,
                    ),
                ]),
            ),
        ];
    }

    public function boot(
        AttributesHandlersLocator $listener,
        TokenizerListenerBootloader $tokenizer,
    ): void {
        $tokenizer->addListener($listener);

//        $registry->register(
//            CreateUser::class,
//            [$handler, '__invoke'],
//        );
    }
}

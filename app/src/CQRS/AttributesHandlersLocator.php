<?php

declare(strict_types=1);

namespace App\CQRS;

use App\CQRS\Attributes\CommandHandler;
use App\CQRS\Attributes\QueryHandler;
use Spiral\Core\Attribute\Singleton;
use Spiral\Core\FactoryInterface;
use Spiral\Tokenizer\Attribute\TargetAttribute;
use Spiral\Tokenizer\TokenizationListenerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;

#[Singleton]
#[TargetAttribute(CommandHandler::class)]
#[TargetAttribute(QueryHandler::class)]
final class AttributesHandlersLocator implements
    HandlersLocatorInterface,
    HandlersRegistryInterface,
    TokenizationListenerInterface
{
    private array $commandHandlers = [];
    private array $queryHandlers = [];

    public function __construct(
        private readonly FactoryInterface $factory,
    ) {}

    public function getHandlers(Envelope $envelope): iterable
    {
        match (true) {
            $envelope->getMessage() instanceof CommandInterface => yield from $this->getCommandHandlers($envelope),
            $envelope->getMessage() instanceof QueryInterface => yield from $this->getQueryHandlers($envelope),
            default => throw new \RuntimeException('Unknown message type'),
        };
    }

    public function register(string $commandClass, callable $handler): void
    {
        if (\is_subclass_of($commandClass, CommandInterface::class)) {
            $this->commandHandlers[$commandClass][] = $handler;
        } elseif (\is_subclass_of($commandClass, QueryInterface::class)) {
            $this->queryHandlers[$commandClass] = $handler;
        } else {
            throw new \RuntimeException('Unknown message type');
        }
    }

    public function listen(\ReflectionClass $class): void
    {
        if (!$class->isInstantiable()) {
            return;
        }

        foreach ($class->getMethods() as $method) {
            if (!$method->isPublic()) {
                continue;
            }

            $this->registerCommandHandler($method);
            $this->registerQueryHandler($method);
        }
    }

    public function finalize(): void
    {
        // do nothing
    }

    private function getCommandHandlers(Envelope $envelope): iterable
    {
        foreach ($this->commandHandlers as $commandClass => $handlers) {
            if (!$envelope->getMessage() instanceof $commandClass) {
                continue;
            }

            foreach ($handlers as $handler) {
                yield new HandlerDescriptor($handler);
            }
        }
    }

    private function getQueryHandlers(Envelope $envelope): iterable
    {
        foreach ($this->queryHandlers as $commandClass => $handler) {
            yield new HandlerDescriptor($handler);
        }
    }

    private function registerClass(\ReflectionMethod $method, string $commandClass, string $requiredInterface): void
    {
        if (!\class_exists($commandClass)) {
            throw new \RuntimeException('Command class does not exist');
        }

        // instance of CommandInterface
        if (!\is_subclass_of($commandClass, $requiredInterface)) {
            throw new \RuntimeException('Command class must implement CommandInterface');
        }

        $handler = $this->factory->make($method->getDeclaringClass()->getName());
        $this->register($commandClass, [$handler, $method->getName()]);
    }

    private function registerCommandHandler(\ReflectionMethod $method): void
    {
        $commandAttributes = $method->getAttributes(CommandHandler::class);
        if (empty($commandAttributes)) {
            return;
        }

        if (\count($method->getParameters()) !== 1) {
            throw new \RuntimeException('Command handler must have exactly one parameter');
        }

        $parameter = $method->getParameters()[0];
        $commandClass = $parameter->getType()->getName();

        $this->registerClass($method, $commandClass, CommandInterface::class);
    }

    private function registerQueryHandler(\ReflectionMethod $method): void
    {
        $attributes = $method->getAttributes(QueryHandler::class);
        if (empty($attributes)) {
            return;
        }

        if (\count($method->getParameters()) !== 1) {
            throw new \RuntimeException('Command handler must have exactly one parameter');
        }

        $parameter = $method->getParameters()[0];
        $commandClass = $parameter->getType()->getName();

        $this->registerClass($method, $commandClass, QueryInterface::class);
    }
}

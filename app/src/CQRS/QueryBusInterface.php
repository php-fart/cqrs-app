<?php

declare(strict_types=1);

namespace App\CQRS;

use Spiral\Prototype\Annotation\Prototyped;

interface QueryBusInterface
{
    public function ask(QueryInterface $query): mixed;
}

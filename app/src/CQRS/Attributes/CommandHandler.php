<?php

declare(strict_types=1);

namespace App\CQRS\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class CommandHandler
{

}

<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use Spiral\Bootloader\DomainBootloader;
use Spiral\Core\CoreInterface;

final class AppBootloader extends DomainBootloader
{
    public function defineSingletons(): array
    {
        return [
            CoreInterface::class => [self::class, 'domainCore'],
        ];
    }

    protected static function defineInterceptors(): array
    {
        return [];
    }
}

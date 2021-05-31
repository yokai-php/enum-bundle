<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Integration\App\Model;

use MyCLabs\Enum\Enum;

/**
 * @method static self OPENED
 * @method static self MERGED
 * @method static self CLOSED
 */
final class Status extends Enum
{
    private const OPENED = 'opened';
    private const MERGED = 'merged';
    private const CLOSED = 'closed';
}

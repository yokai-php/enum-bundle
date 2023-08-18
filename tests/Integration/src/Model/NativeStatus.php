<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Integration\App\Model;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
enum NativeStatus: string
{
    case OPENED = 'opened';
    case MERGED = 'merged';
    case CLOSED = 'closed';
}

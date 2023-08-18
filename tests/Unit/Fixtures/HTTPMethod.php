<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit\Fixtures;

enum HTTPMethod: string
{
    case GET = 'get';
    case POST = 'post';
}

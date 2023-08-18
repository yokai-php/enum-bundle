<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit\Fixtures;

enum HTTPStatus: int
{
    case OK = 200;
    case NOT_FOUND = 404;
}

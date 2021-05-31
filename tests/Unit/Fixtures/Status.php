<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit\Fixtures;

use MyCLabs\Enum\Enum;

/**
 * Enum from "myclabs/php-enum".
 *
 * @method static self SUCCESS
 * @method static self ERROR
 *
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class Status extends Enum
{
    private const SUCCESS = 0;
    private const ERROR = 1;
}

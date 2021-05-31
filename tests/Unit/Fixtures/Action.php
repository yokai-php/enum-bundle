<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit\Fixtures;

use MyCLabs\Enum\Enum;

/**
 * Enum from "myclabs/php-enum".
 *
 * @method static self VIEW
 * @method static self EDIT
 *
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class Action extends Enum
{
    private const VIEW = 'view';
    private const EDIT = 'edit';
}

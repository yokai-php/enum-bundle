<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit\Fixtures;

use Yokai\EnumBundle\Enum;

/**
 * Inherit implementation from base class.
 * Values are hardcoded as a constructor argument.
 * Each value label is hardcoded as a constructor argument.
 *
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class TypeEnum extends Enum
{
    public function __construct()
    {
        parent::__construct(['Customer' => 'customer', 'Prospect' => 'prospect'], 'type');
    }
}

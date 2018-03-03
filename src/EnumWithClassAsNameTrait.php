<?php

namespace Yokai\Enum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
trait EnumWithClassAsNameTrait
{
    /**
     * @return string
     */
    public function getName()
    {
        return static::class;
    }
}

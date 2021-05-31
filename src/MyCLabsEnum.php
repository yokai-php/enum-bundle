<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use MyCLabs\Enum\Enum as ActualMyCLabsEnum;
use Yokai\EnumBundle\Exception\LogicException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class MyCLabsEnum extends Enum
{
    /**
     * @param string      $enum
     * @param string|null $name
     */
    public function __construct(string $enum, string $name = null)
    {
        if (!\is_a($enum, ActualMyCLabsEnum::class, true)) {
            throw LogicException::invalidMyClabsEnumClass($enum);
        }

        if ($name === null && static::class === __CLASS__) {
            $name = $enum;
        }

        parent::__construct($enum::values(), $name);
    }
}

<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use UnitEnum;
use Yokai\EnumBundle\Exception\LogicException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class NativeEnum extends Enum
{
    /**
     * @param string      $enum
     * @param string|null $name
     */
    public function __construct(string $enum, string $name = null)
    {
        if (!\is_a($enum, UnitEnum::class, true)) {
            throw LogicException::invalidUnitEnum($enum);
        }

        if ($name === null && static::class === __CLASS__) {
            $name = $enum;
        }

        $choices = [];
        foreach ($enum::cases() as $case) {
            $choices[$case->name] = $case;
        }

        parent::__construct($choices, $name);
    }
}

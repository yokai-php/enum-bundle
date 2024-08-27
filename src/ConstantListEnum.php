<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use Yokai\EnumBundle\Exception\LogicException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConstantListEnum extends Enum
{
    public function __construct(string $constantsPattern, ?string $name = null)
    {
        $choices = [];
        foreach (ConstantExtractor::extract($constantsPattern) as $value) {
            if (!\is_string($value) && !\is_int($value)) {
                throw new LogicException(
                    \sprintf('Extracted constant enum value must be string or int, %s given.', \get_debug_type($value)),
                );
            }
            $choices[(string)$value] = $value;
        }
        parent::__construct($choices, $name);
    }
}

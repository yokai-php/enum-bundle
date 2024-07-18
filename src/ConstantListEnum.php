<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConstantListEnum extends Enum
{
    public function __construct(string $constantsPattern, ?string $name = null)
    {
        $values = ConstantExtractor::extract($constantsPattern);
        parent::__construct(\array_combine($values, $values), $name);
    }
}

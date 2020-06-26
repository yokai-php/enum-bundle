<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConstantListEnum extends ConfigurableEnum
{
    /**
     * @inheritDoc
     */
    public function __construct(string $constantsPattern, string $name)
    {
        $values = ConstantExtractor::extract($constantsPattern);
        parent::__construct($name, array_combine($values, $values));
    }
}

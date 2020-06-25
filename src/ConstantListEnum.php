<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

class ConstantListEnum extends ConfigurableEnum
{
    /**
     * @inheritDoc
     */
    public function __construct(ConstantExtractor $extractor, string $constantsPattern, string $name)
    {
        $values = $extractor->extract($constantsPattern);
        parent::__construct($name, array_combine($values, $values));
    }
}

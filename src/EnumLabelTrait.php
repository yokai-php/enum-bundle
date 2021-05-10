<?php

namespace Yokai\EnumBundle;

use Yokai\EnumBundle\Exception\InvalidEnumValueException;

trait EnumLabelTrait
{
    /**
     * @inheritdoc
     */
    public function getLabel(string $value): string
    {
        if (!isset($this->getChoices()[$value])) {
            throw InvalidEnumValueException::create($this, $value);
        }

        return $this->getChoices()[$value];
    }
}

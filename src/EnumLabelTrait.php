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
        $choices = $this->getChoices();
        if (!isset($choices[$value])) {
            throw InvalidEnumValueException::create($this, $value);
        }

        return $choices[$value];
    }
}

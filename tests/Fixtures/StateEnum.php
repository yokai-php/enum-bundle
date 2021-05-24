<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Fixtures;

use Yokai\EnumBundle\EnumInterface;
use Yokai\EnumBundle\Exception\InvalidArgumentException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class StateEnum implements EnumInterface
{
    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return __CLASS__;
    }

    /**
     * @inheritDoc
     */
    public function getValues(): array
    {
        return \array_values($this->getChoices());
    }

    /**
     * @inheritDoc
     */
    public function getChoices(): array
    {
        return ['New' => 'new', 'Validated' => 'validated', 'Disabled' => 'disabled'];
    }

    /**
     * @inheritdoc
     */
    public function getLabel($value): string
    {
        $choices = $this->getChoices();
        if (!isset($choices[$value])) {
            throw InvalidArgumentException::enumMissingValue($this, $value);
        }

        return $choices[$value];
    }
}

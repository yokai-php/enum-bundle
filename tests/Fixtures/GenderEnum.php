<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Fixtures;

use Yokai\EnumBundle\EnumInterface;
use Yokai\EnumBundle\EnumWithClassAsNameTrait;
use Yokai\EnumBundle\Exception\InvalidEnumValueException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class GenderEnum implements EnumInterface
{
    use EnumWithClassAsNameTrait;

    public function getChoices(): array
    {
        return ['male' => 'Male', 'female' => 'Female'];
    }

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

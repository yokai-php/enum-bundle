<?php

namespace Yokai\Enum\Tests\Fixtures;

use Yokai\Enum\EnumInterface;
use Yokai\Enum\EnumWithClassAsNameTrait;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class GenderEnum implements EnumInterface
{
    use EnumWithClassAsNameTrait;

    public function getChoices()
    {
        return ['male' => 'Male', 'female' => 'Female'];
    }
}

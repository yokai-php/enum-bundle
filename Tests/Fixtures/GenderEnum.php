<?php

namespace Yokai\EnumBundle\Tests\Fixtures;

use Yokai\EnumBundle\Enum\EnumInterface;
use Yokai\EnumBundle\Enum\EnumWithClassAsNameTrait;

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

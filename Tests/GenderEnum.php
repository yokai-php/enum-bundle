<?php

namespace EnumBundle\Tests;

use EnumBundle\Enum\EnumInterface;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class GenderEnum implements EnumInterface
{
    public function getChoices()
    {
        return ['male' => 'Male', 'female' => 'Female'];
    }

    public function getName()
    {
        return 'gender';
    }
}

<?php

namespace EnumBundle\Tests;

use EnumBundle\Enum\EnumInterface;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class TypeEnum implements EnumInterface
{
    public function getChoices()
    {
        return ['customer' => 'Customer', 'prospect' => 'Prospect'];
    }

    public function getName()
    {
        return 'type';
    }
}

<?php

namespace Octo\EnumBundle\Tests;

use Octo\EnumBundle\Enum\EnumInterface;

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

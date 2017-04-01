<?php

namespace Acme\Bundle\AppBundle\Enum;

use Yokai\EnumBundle\Enum\EnumInterface;

/**
 * @author Yann Eugoné <yeugone@prestaconcept.net>
 */
class DummyEnum implements EnumInterface
{
    public function getChoices()
    {
        return ['foo' => 'Foo', 'bar' => 'Bar'];
    }

    public function getName()
    {
        return 'dummy';
    }
}

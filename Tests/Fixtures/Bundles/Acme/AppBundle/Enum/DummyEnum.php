<?php

namespace Acme\AppBundle\Enum;

use EnumBundle\Enum\EnumInterface;

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

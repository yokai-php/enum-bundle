<?php

namespace Acme\Bundle\AppBundle\Enum\Customer;

use EnumBundle\Enum\AbstractTranslatedEnum;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class StateEnum extends AbstractTranslatedEnum
{
    protected function getValues()
    {
        return ['new', 'validated', 'disabled'];
    }

    public function getName()
    {
        return 'state';
    }
}

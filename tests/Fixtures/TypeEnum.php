<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Fixtures;

use Yokai\EnumBundle\ConfigurableEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class TypeEnum extends ConfigurableEnum
{
    public function __construct()
    {
        parent::__construct('type', ['customer' => 'Customer', 'prospect' => 'Prospect']);
    }
}

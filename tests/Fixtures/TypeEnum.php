<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Fixtures;

use Yokai\EnumBundle\Enum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class TypeEnum extends Enum
{
    public function __construct()
    {
        parent::__construct('type', ['Customer' => 'customer', 'Prospect' => 'prospect']);
    }
}

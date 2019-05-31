<?php declare(strict_types=1);

namespace Yokai\Enum\Tests\Fixtures;

use Yokai\Enum\ConfigurableEnum;

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

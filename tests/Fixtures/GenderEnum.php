<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Fixtures;

use Yokai\EnumBundle\EnumInterface;
use Yokai\EnumBundle\EnumWithClassAsNameTrait;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class GenderEnum implements EnumInterface
{
    use EnumWithClassAsNameTrait;

    public function getChoices(): array
    {
        return ['male' => 'Male', 'female' => 'Female'];
    }
}

<?php declare(strict_types=1);

namespace Yokai\EnumBundle;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
trait EnumWithClassAsNameTrait
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return static::class;
    }
}

<?php declare(strict_types=1);

namespace Yokai\Enum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
interface EnumInterface
{
    /**
     * @return array
     */
    public function getChoices(): array;

    /**
     * @return string
     */
    public function getName(): string;
}

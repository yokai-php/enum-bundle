<?php

namespace Yokai\EnumBundle\Enum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
interface EnumInterface
{
    /**
     * @return array
     */
    public function getChoices();

    /**
     * @return string
     */
    public function getName();
}

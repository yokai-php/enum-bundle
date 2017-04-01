<?php

namespace Yokai\EnumBundle\Exception;

use BadMethodCallException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class DuplicatedEnumException extends BadMethodCallException
{
    /**
     * @param string $name
     *
     * @return DuplicatedEnumException
     */
    public static function alreadyRegistered($name)
    {
        return new self(sprintf('Enum with name "%s" is already registered', $name));
    }
}

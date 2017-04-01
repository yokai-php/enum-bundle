<?php

namespace Yokai\EnumBundle\Exception;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class DuplicatedEnumException extends \BadMethodCallException
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

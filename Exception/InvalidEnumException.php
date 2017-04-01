<?php

namespace Yokai\EnumBundle\Exception;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class InvalidEnumException extends \DomainException
{
    /**
     * @param string $name
     *
     * @return InvalidEnumException
     */
    public static function nonexistent($name)
    {
        return new self(sprintf('Nonexistent enum with name "%s" in registry', $name));
    }
}

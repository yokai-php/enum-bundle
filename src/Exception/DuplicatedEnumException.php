<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Exception;

use BadMethodCallException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class DuplicatedEnumException extends BadMethodCallException implements ExceptionInterface
{
    /**
     * @param string $name
     *
     * @return DuplicatedEnumException
     */
    public static function alreadyRegistered(string $name): self
    {
        return new self(sprintf('Enum with name "%s" is already registered', $name));
    }
}

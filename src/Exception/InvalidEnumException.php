<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Exception;

use DomainException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class InvalidEnumException extends DomainException implements ExceptionInterface
{
    /**
     * @param string $name
     *
     * @return InvalidEnumException
     */
    public static function nonexistent(string $name): self
    {
        return new self(sprintf('Nonexistent enum with name "%s" in registry', $name));
    }
}

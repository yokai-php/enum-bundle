<?php declare(strict_types=1);

namespace Yokai\Enum\Exception;

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
    public static function alreadyRegistered(string $name): self
    {
        return new self(sprintf('Enum with name "%s" is already registered', $name));
    }
}

<?php

namespace Yokai\EnumBundle\Exception;

use InvalidArgumentException;
use Yokai\EnumBundle\EnumInterface;

final class InvalidEnumValueException extends InvalidArgumentException implements ExceptionInterface
{
    public static function create(EnumInterface $enum, string $value): self
    {
        return new self(
            \sprintf('Enum "%s" does not have "%s" value.', $enum->getName(), $value)
        );
    }
}

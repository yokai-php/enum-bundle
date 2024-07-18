<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Exception;

use Yokai\EnumBundle\EnumInterface;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function unregisteredEnum(string $name): self
    {
        return new self(\sprintf(
            'Enum with name "%s" was not registered in registry',
            $name
        ));
    }

    public static function enumMissingValue(EnumInterface $enum, mixed $value): self
    {
        if (\is_object($value) && \method_exists($value, '__toString')) {
            $value = (string)$value;
        }
        if (!\is_string($value)) {
            $value = \get_debug_type($value);
        }

        return new self(\sprintf(
            'Enum "%s" does not have "%s" value.',
            $enum->getName(),
            $value
        ));
    }
}

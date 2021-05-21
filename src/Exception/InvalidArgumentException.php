<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Exception;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function unregisteredEnum(string $name): self
    {
        return new self(sprintf(
            'Enum with name "%s" was not registered in registry',
            $name
        ));
    }
}

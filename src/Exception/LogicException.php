<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Exception;

final class LogicException extends \LogicException implements ExceptionInterface
{
    public static function cannotExtractConstants(string $pattern, string $reason): self
    {
        return new self(\sprintf(
            'Cannot extract constants for pattern "%s". %s',
            $pattern,
            $reason
        ));
    }

    public static function placeholderRequired(string $transPattern): self
    {
        return new self(sprintf(
            'Translation pattern "%s" must contain %%s placeholder.',
            $transPattern
        ));
    }

    public static function alreadyRegistered(string $name): self
    {
        return new self(sprintf(
            'Enum with name "%s" is already registered.',
            $name
        ));
    }
}

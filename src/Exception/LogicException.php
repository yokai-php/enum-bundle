<?php

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
}

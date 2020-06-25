<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Exception;

use InvalidArgumentException;

final class CannotExtractConstantsException extends InvalidArgumentException
{
    public static function invalidPattern(string $pattern): self
    {
        return new self(
            "Constant extraction pattern must look like Fully\\Qualified\\ClassName::CONSTANT_*. Got $pattern."
        );
    }

    public static function classDoNotExists(string $class): self
    {
        return new self("Class $class do not exists.");
    }

    public static function classHasNoPublicConstant(string $class): self
    {
        return new self("Class $class has no public constant.");
    }

    public static function noConstantMathingPattern(string $pattern): self
    {
        return new self("Pattern $pattern matches no constant.");
    }
}

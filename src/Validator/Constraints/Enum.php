<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Choice;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
final class Enum extends Choice
{
    /**
     * @var string
     */
    public $enum;

    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        array $options = [],
        string|null $enum = null,
        callable|null|string $callback = null,
        bool $multiple = null,
        bool $strict = null,
        int $min = null,
        int $max = null,
        string $message = null,
        string $multipleMessage = null,
        string $minMessage = null,
        string $maxMessage = null,
        array|null $groups = null,
        mixed $payload = null,
    ) {
        if (\is_string($enum)) {
            $this->enum = $enum;
        }

        // Since Symfony 5.3, first argument of Choice is $options
        parent::__construct(
            $options,
            null,
            $callback,
            $multiple,
            $strict,
            $min,
            $max,
            $message,
            $multipleMessage,
            $minMessage,
            $maxMessage,
            $groups,
            $payload
        );
    }

    public function getDefaultOption(): string
    {
        return 'enum';
    }

    public function validatedBy(): string
    {
        return 'yokai_enum.validator_constraints.enum_validator';
    }
}

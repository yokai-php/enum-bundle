<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Choice;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD)]
final class Enum extends Choice
{
    public string $enum;

    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        array $options = [],
        string|null $enum = null,
        callable|null|string $callback = null,
        bool|null $multiple = null,
        bool|null $strict = null,
        int|null $min = null,
        int|null $max = null,
        string|null $message = null,
        string|null $multipleMessage = null,
        string|null $minMessage = null,
        string|null $maxMessage = null,
        array|null $groups = null,
        mixed $payload = null,
    ) {
        if (\is_array($options) && $options !== []) {
            \trigger_deprecation(
                'symfony/validator',
                '7.3',
                'Passing an array of options to configure the "%s" constraint is deprecated, use named arguments instead.',
                static::class,
            );
        }

        if (\is_string($enum)) {
            $this->enum = $enum;
        }

        // Since Symfony 5.3, first argument of Choice is $options
        parent::__construct(
            [],
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

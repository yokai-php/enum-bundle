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

    public function __construct(
        $enum = null,
        $callback = null,
        bool $multiple = null,
        bool $strict = null,
        int $min = null,
        int $max = null,
        string $message = null,
        string $multipleMessage = null,
        string $minMessage = null,
        string $maxMessage = null,
        $groups = null,
        $payload = null,
        array $options = []
    ) {
        if (\is_array($enum)) {
            // Symfony 4.4 Constraints has single constructor argument containing all options
            parent::__construct($enum);
        } else {
            if (\is_string($enum)) {
                $this->enum = $enum;
            }

            // Symfony 5.x Constraints has many constructor arguments for PHP 8.0 Attributes support
            parent::__construct(
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
                $payload,
                $options
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function getDefaultOption(): string
    {
        return 'enum';
    }

    /**
     * @inheritdoc
     */
    public function validatedBy(): string
    {
        return 'yokai_enum.validator_constraints.enum_validator';
    }
}

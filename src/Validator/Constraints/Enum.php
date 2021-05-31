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
final class Enum extends Choice
{
    /**
     * @var string
     */
    public $enum;

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

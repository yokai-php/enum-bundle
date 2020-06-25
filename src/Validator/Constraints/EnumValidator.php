<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\ChoiceValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Yokai\EnumBundle\EnumRegistry;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumValidator extends ChoiceValidator
{
    /**
     * @var EnumRegistry
     */
    private $enumRegistry;

    /**
     * @param EnumRegistry $enumRegistry
     */
    public function __construct(EnumRegistry $enumRegistry)
    {
        $this->enumRegistry = $enumRegistry;
    }

    /**
     * @inheritdoc
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Enum) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\Enum');
        }

        $constraint->choices  = null;
        $constraint->callback = null;

        if (!$constraint->enum) {
            throw new ConstraintDefinitionException('"enum" must be specified on constraint Enum');
        }

        if (!$this->enumRegistry->has($constraint->enum)) {
            throw new ConstraintDefinitionException(sprintf(
                '"enum" "%s" on constraint Enum does not exist',
                $constraint->enum
            ));
        }

        $constraint->choices = array_keys($this->enumRegistry->get($constraint->enum)->getChoices());

        parent::validate($value, $constraint);
    }
}

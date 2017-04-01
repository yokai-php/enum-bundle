<?php

namespace Yokai\EnumBundle\Validator\Constraints;

use Yokai\EnumBundle\Registry\EnumRegistryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\ChoiceValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumValidator extends ChoiceValidator
{
    /**
     * @var EnumRegistryInterface
     */
    private $enumRegistry;

    /**
     * @param EnumRegistryInterface $enumRegistry
     */
    public function __construct(EnumRegistryInterface $enumRegistry)
    {
        $this->enumRegistry = $enumRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Enum) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Enum');
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

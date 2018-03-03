<?php

namespace Yokai\Enum\Bridge\Symfony\Form\Extension;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Yokai\Enum\Bridge\Symfony\Form\Type\EnumType;
use Yokai\Enum\Bridge\Symfony\Validator\Constraints\Enum;
use Yokai\Enum\EnumRegistry;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumTypeGuesser extends ValidatorTypeGuesser
{
    /**
     * @var EnumRegistry
     */
    private $enumRegistry;

    /**
     * @var string
     */
    private $enumFormType;

    /**
     * @param MetadataFactoryInterface $metadataFactory
     * @param EnumRegistry             $enumRegistry
     */
    public function __construct(MetadataFactoryInterface $metadataFactory, EnumRegistry $enumRegistry)
    {
        parent::__construct($metadataFactory);

        $this->enumRegistry = $enumRegistry;

        if (method_exists(AbstractType::class, 'getBlockPrefix')) {
            $this->enumFormType = EnumType::class; //Symfony 3.x support
        } else {
            $this->enumFormType = 'enum'; //Symfony 2.x support
        }
    }

    /**
     * @inheritdoc
     */
    public function guessTypeForConstraint(Constraint $constraint)
    {
        if (!$constraint instanceof Enum) {
            return null;
        }

        return new TypeGuess(
            $this->enumFormType,
            [
                'enum' => $constraint->enum,
                'multiple' => $constraint->multiple,
            ],
            Guess::HIGH_CONFIDENCE
        );
    }

    /**
     * @inheritDoc
     */
    public function guessRequired($class, $property)
    {
        return null; //override parent : not able to guess
    }

    /**
     * @inheritDoc
     */
    public function guessMaxLength($class, $property)
    {
        return null; //override parent : not able to guess
    }

    /**
     * @inheritDoc
     */
    public function guessPattern($class, $property)
    {
        return null; //override parent : not able to guess
    }
}

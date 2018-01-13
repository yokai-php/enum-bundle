<?php

namespace Yokai\EnumBundle\Form\Extension;

use Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Yokai\EnumBundle\Registry\EnumRegistryInterface;
use Yokai\EnumBundle\Validator\Constraints\Enum;

class EnumTypeGuesser extends ValidatorTypeGuesser
{
    /**
     * @var EnumRegistryInterface
     */
    private $enumRegistry;

    /**
     * @param MetadataFactoryInterface $metadataFactory
     * @param EnumRegistryInterface    $enumRegistry
     */
    public function __construct(MetadataFactoryInterface $metadataFactory, EnumRegistryInterface $enumRegistry)
    {
        parent::__construct($metadataFactory);

        $this->enumRegistry = $enumRegistry;
    }

    /**
     * @inheritDoc
     */
    public function guessRequired($class, $property)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function guessMaxLength($class, $property)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function guessPattern($class, $property)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function guessTypeForConstraint(Constraint $constraint)
    {
        switch (get_class($constraint)) {
            case 'Yokai\EnumBundle\Validator\Constraints\Enum':
                /** @var $constraint Enum */
                return new TypeGuess(
                    'Yokai\EnumBundle\Form\Type\EnumType',
                    [
                        'enum' => $constraint->enum,
                        'multiple' => $constraint->multiple,
                    ],
                    Guess::HIGH_CONFIDENCE
                );
        }

        return null;
    }
}

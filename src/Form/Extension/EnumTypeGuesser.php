<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Form\Extension;

use Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Guess\ValueGuess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Form\Type\EnumType;
use Yokai\EnumBundle\Validator\Constraints\Enum;

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
     * @param MetadataFactoryInterface $metadataFactory
     * @param EnumRegistry             $enumRegistry
     */
    public function __construct(MetadataFactoryInterface $metadataFactory, EnumRegistry $enumRegistry)
    {
        parent::__construct($metadataFactory);

        $this->enumRegistry = $enumRegistry;
    }

    /**
     * @inheritdoc
     */
    public function guessTypeForConstraint(Constraint $constraint): ?TypeGuess
    {
        if (!$constraint instanceof Enum) {
            return null;
        }

        return new TypeGuess(
            EnumType::class,
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
    public function guessRequired($class, $property): ?ValueGuess
    {
        return null; //override parent : not able to guess
    }

    /**
     * @inheritDoc
     */
    public function guessMaxLength($class, $property): ?ValueGuess
    {
        return null; //override parent : not able to guess
    }

    /**
     * @inheritDoc
     */
    public function guessPattern($class, $property): ?ValueGuess
    {
        return null; //override parent : not able to guess
    }
}

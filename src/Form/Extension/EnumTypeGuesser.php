<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Form\Extension;

use Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Guess\ValueGuess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Compound;
use Yokai\EnumBundle\Form\Type\EnumType;
use Yokai\EnumBundle\Validator\Constraints\Enum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class EnumTypeGuesser extends ValidatorTypeGuesser
{
    public function guessTypeForConstraint(Constraint $constraint): ?TypeGuess
    {
        $enum = $this->getEnum($constraint);
        if ($enum === null) {
            return null;
        }

        return new TypeGuess(
            EnumType::class,
            [
                'enum' => $enum->enum,
                'multiple' => $enum->multiple,
            ],
            Guess::HIGH_CONFIDENCE
        );
    }

    public function guessRequired(string $class, string $property): ?ValueGuess
    {
        return null; //override parent : not able to guess
    }

    public function guessMaxLength(string $class, string $property): ?ValueGuess
    {
        return null; //override parent : not able to guess
    }

    public function guessPattern(string $class, string $property): ?ValueGuess
    {
        return null; //override parent : not able to guess
    }

    private function getEnum(Constraint $constraint): ?Enum
    {
        if ($constraint instanceof Enum) {
            return $constraint;
        }

        if ($constraint instanceof Compound) {
            foreach ($constraint->constraints as $compositeConstraint) {
                $enum = $this->getEnum($compositeConstraint);
                if ($enum !== null) {
                    return $enum;
                }
            }
        }

        return null;
    }
}

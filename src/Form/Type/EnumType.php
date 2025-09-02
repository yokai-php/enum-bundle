<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Form\Type;

use MyCLabs\Enum\Enum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yokai\EnumBundle\EnumRegistry;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class EnumType extends AbstractType
{
    private EnumRegistry $enumRegistry;

    public function __construct(EnumRegistry $enumRegistry)
    {
        $this->enumRegistry = $enumRegistry;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined(['enum', 'enum_choice_value'])
            ->setRequired('enum')
            ->setAllowedValues(
                'enum',
                function (string $name): bool {
                    return $this->enumRegistry->has($name);
                }
            )
            ->setDefault(
                'choices',
                function (Options $options): array {
                    /** @var string $name */
                    $name = $options['enum'];

                    return $this->enumRegistry->get($name)->getChoices();
                }
            )
            ->setAllowedTypes('enum_choice_value', ['bool'])
            ->setDefault('enum_choice_value', true)
            ->setDefault(
                'choice_value',
                static function (Options $options) {
                    if (!$options['enum_choice_value']) {
                        return null;
                    }

                    return function ($value) {
                        if ($value instanceof \BackedEnum) {
                            return $value->value;
                        }
                        if ($value instanceof \UnitEnum) {
                            return $value->name;
                        }
                        if ($value instanceof Enum) {
                            return $value->getValue();
                        }

                        return $value;
                    };
                }
            )
        ;
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'yokai_enum';
    }
}

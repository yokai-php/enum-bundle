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
                    $choices = $this->enumRegistry->get($options['enum'])->getChoices();

                    if ($options['enum_choice_value'] === null) {
                        foreach ($choices as $value) {
                            if (!\is_scalar($value)) {
                                @\trigger_error(
                                    'Not configuring the "enum_choice_value" option is deprecated.' .
                                    ' It will default to "true" in 5.0.',
                                    \E_USER_DEPRECATED
                                );
                                break;
                            }
                        }
                    }

                    return $choices;
                }
            )
            ->setAllowedTypes('enum_choice_value', ['bool', 'null'])
            ->setDefault('enum_choice_value', null)
            ->setDefault(
                'choice_value',
                static function (Options $options) {
                    if ($options['enum_choice_value'] !== true) {
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

    /**
     * @inheritdoc
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix(): string
    {
        return 'yokai_enum';
    }
}

<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yokai\EnumBundle\EnumRegistry;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumType extends AbstractType
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
                    return array_flip($this->enumRegistry->get($options['enum'])->getChoices());
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

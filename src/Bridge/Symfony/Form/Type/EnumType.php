<?php declare(strict_types=1);

namespace Yokai\Enum\Bridge\Symfony\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Yokai\Enum\EnumRegistry;

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
            ->setDefault('choices_as_values', true)
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
        if (!method_exists(AbstractType::class, 'getBlockPrefix')) {
            return 'choice'; //Symfony 2.x support
        }

        return ChoiceType::class;
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix(): string
    {
        return 'enum';
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->getBlockPrefix();
    }
}

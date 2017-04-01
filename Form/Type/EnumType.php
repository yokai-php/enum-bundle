<?php

namespace Yokai\EnumBundle\Form\Type;

use Yokai\EnumBundle\Registry\EnumRegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumType extends AbstractType
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('enum')
            ->setDefault('choices_as_values', true)
            ->setAllowedValues(
                'enum',
                function ($name) {
                    return $this->enumRegistry->has($name);
                }
            )
            ->setDefault(
                'choices',
                function (Options $options) {
                    return array_flip($this->enumRegistry->get($options['enum'])->getChoices());
                }
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        if (!method_exists(AbstractType::class, 'getBlockPrefix')) {
            return 'choice'; //Symfony 2.x support
        }

        return ChoiceType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'enum';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}

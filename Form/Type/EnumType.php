<?php

namespace Octo\EnumBundle\Form\Type;

use Octo\EnumBundle\Registry\EnumRegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
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
            ->setAllowedValues(
                'enum',
                function ($name) {
                    return $this->enumRegistry->has($name);
                }
            )
            ->setDefault(
                'choices',
                function (Options $options) {
                    return $this->enumRegistry->get($options['enum'])->getChoices();
                }
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'enum';
    }
}

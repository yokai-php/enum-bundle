<?php

namespace EnumBundle\Twig\Extension;

use EnumBundle\Registry\EnumRegistryInterface;
use Twig_Extension;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class EnumExtension extends Twig_Extension
{
    /**
     * @var EnumRegistryInterface
     */
    private $registry;

    /**
     * @param EnumRegistryInterface $registry
     */
    public function __construct(EnumRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('enum_label', [ $this, 'getLabel' ]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('enum_label', [ $this, 'getLabel' ]),
        ];
    }

    /**
     * @param string $value
     * @param string $enum
     *
     * @return string
     */
    public function getLabel($value, $enum)
    {
        $choices = $this->registry->get($enum)->getChoices();

        if (isset($choices[$value])) {
            return $choices[$value];
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'enum';
    }
}

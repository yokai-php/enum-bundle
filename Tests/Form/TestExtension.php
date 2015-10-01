<?php

namespace EnumBundle\Tests\Form;

use EnumBundle\Form\Type\EnumType;
use EnumBundle\Registry\EnumRegistryInterface;
use Symfony\Component\Form\AbstractExtension;
use Symfony\Component\Form\Exception;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class TestExtension extends AbstractExtension
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
    protected function loadTypes()
    {
        return [
            new EnumType($this->enumRegistry),
        ];
    }
}

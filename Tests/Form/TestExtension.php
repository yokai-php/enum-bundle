<?php

namespace Yokai\EnumBundle\Tests\Form;

use Yokai\EnumBundle\Form\Type\EnumType;
use Yokai\EnumBundle\Registry\EnumRegistryInterface;
use Symfony\Component\Form\AbstractExtension;
use Symfony\Component\Form\Exception;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
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

<?php

namespace Yokai\EnumBundle\Tests\Form;

use Symfony\Component\Form\AbstractExtension;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Yokai\EnumBundle\Form\Extension\EnumTypeGuesser;
use Yokai\EnumBundle\Form\Type\EnumType;
use Yokai\EnumBundle\Registry\EnumRegistryInterface;

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
     * @var MetadataFactoryInterface|null
     */
    private $metadataFactory;

    /**
     * @param EnumRegistryInterface         $enumRegistry
     * @param MetadataFactoryInterface|null $metadataFactory
     */
    public function __construct(EnumRegistryInterface $enumRegistry, MetadataFactoryInterface $metadataFactory = null)
    {
        $this->enumRegistry = $enumRegistry;
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * @inheritdoc
     */
    protected function loadTypes()
    {
        return [
            new EnumType($this->enumRegistry),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function loadTypeGuesser()
    {
        if ($this->metadataFactory === null) {
            return null;
        }

        return new EnumTypeGuesser($this->metadataFactory, $this->enumRegistry);
    }
}

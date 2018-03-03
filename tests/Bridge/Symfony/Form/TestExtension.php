<?php

namespace Yokai\Enum\Tests\Bridge\Symfony\Form;

use Symfony\Component\Form\AbstractExtension;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Yokai\Enum\Bridge\Symfony\Form\Type\EnumType;
use Yokai\Enum\Bridge\Symfony\Form\Extension\EnumTypeGuesser;
use Yokai\Enum\EnumRegistry;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class TestExtension extends AbstractExtension
{
    /**
     * @var EnumRegistry
     */
    private $enumRegistry;

    /**
     * @var MetadataFactoryInterface|null
     */
    private $metadataFactory;

    /**
     * @param EnumRegistry                  $enumRegistry
     * @param MetadataFactoryInterface|null $metadataFactory
     */
    public function __construct(EnumRegistry $enumRegistry, MetadataFactoryInterface $metadataFactory = null)
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

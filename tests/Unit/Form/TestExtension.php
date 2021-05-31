<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit\Form;

use Symfony\Component\Form\AbstractExtension;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Form\Extension\EnumTypeGuesser;
use Yokai\EnumBundle\Form\Type\EnumType;

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
    protected function loadTypes(): array
    {
        return [
            new EnumType($this->enumRegistry),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function loadTypeGuesser(): ?EnumTypeGuesser
    {
        if ($this->metadataFactory === null) {
            return null;
        }

        return new EnumTypeGuesser($this->metadataFactory, $this->enumRegistry);
    }
}

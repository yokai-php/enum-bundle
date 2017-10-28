<?php

namespace Yokai\EnumBundle\Tests\Form;

use Symfony\Component\Form\AbstractExtension;
use Symfony\Component\Translation\TranslatorInterface;
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
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param EnumRegistryInterface $enumRegistry
     * @param TranslatorInterface $translator
     */
    public function __construct(EnumRegistryInterface $enumRegistry, TranslatorInterface $translator)
    {
        $this->enumRegistry = $enumRegistry;
        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
    protected function loadTypes()
    {
        return [
            new EnumType($this->enumRegistry, $this->translator),
        ];
    }
}

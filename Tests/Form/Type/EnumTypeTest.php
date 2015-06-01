<?php

namespace Octo\EnumBundle\Tests\Form\Type;

use Octo\EnumBundle\Tests\Form\TestExtension;
use Octo\EnumBundle\Tests\GenderEnum;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class EnumTypeTest extends TypeTestCase
{
    private $enumRegistry;

    protected function setUp()
    {
        $this->enumRegistry = $this->prophesize('Octo\EnumBundle\Registry\EnumRegistryInterface');
        $this->enumRegistry->has('state')->willReturn(false);
        $this->enumRegistry->has('gender')->willReturn(true);
        $this->enumRegistry->get('gender')->willReturn(new GenderEnum);

        parent::setUp();
    }

    public function testEnumOptionIsRequired()
    {
        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\MissingOptionsException');
        $this->factory->create('enum');
    }

    public function testEnumOptionIsInvalid()
    {
        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        $this->factory->create('enum', null, ['enum' => 'state']);
    }

    public function testEnumOptionValid()
    {
        $form = $this->factory->create('enum', null, ['enum' => 'gender']);

        $this->assertEquals(['male' => 'Male', 'female' => 'Female'], $form->getConfig()->getOption('choices'));
    }

    protected function getExtensions()
    {
        return [
            new TestExtension($this->enumRegistry->reveal())
        ];
    }
}

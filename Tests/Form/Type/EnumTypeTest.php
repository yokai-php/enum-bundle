<?php

namespace EnumBundle\Tests\Form\Type;

use EnumBundle\Form\Type\EnumType;
use EnumBundle\Tests\Form\TestExtension;
use EnumBundle\Tests\GenderEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class EnumTypeTest extends TypeTestCase
{
    private $enumRegistry;

    protected function setUp()
    {
        $this->enumRegistry = $this->prophesize('EnumBundle\Registry\EnumRegistryInterface');
        $this->enumRegistry->has('state')->willReturn(false);
        $this->enumRegistry->has('gender')->willReturn(true);
        $this->enumRegistry->get('gender')->willReturn(new GenderEnum);

        parent::setUp();
    }

    public function testEnumOptionIsRequired()
    {
        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\MissingOptionsException');
        $this->createForm();
    }

    public function testEnumOptionIsInvalid()
    {
        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        $this->createForm('state');
    }

    public function testEnumOptionValid()
    {
        $form = $this->createForm('gender');

        $this->assertEquals(['Male' => 'male', 'Female' => 'female'], $form->getConfig()->getOption('choices'));
    }

    protected function getExtensions()
    {
        return [
            new TestExtension($this->enumRegistry->reveal())
        ];
    }

    private function createForm($enum = null)
    {
        $options = [];
        if ($enum) {
            $options['enum'] = $enum;
        }

        if (method_exists(AbstractType::class, 'getBlockPrefix')) {
            $name = EnumType::class; //Symfony 3.x support
        } else {
            $name = 'enum'; //Symfony 2.x support
        }

        return $this->factory->create($name, null, $options);
    }
}

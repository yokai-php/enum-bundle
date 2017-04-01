<?php

namespace Yokai\EnumBundle\Tests\Twig\Extension;

use Yokai\EnumBundle\Registry\EnumRegistryInterface;
use Yokai\EnumBundle\Twig\Extension\EnumExtension;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class EnumExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EnumRegistryInterface|\Prophecy\Prophecy\ObjectProphecy
     */
    private $registry;

    protected function setUp()
    {
        $this->registry = $this->prophesize('Yokai\EnumBundle\Registry\EnumRegistryInterface');
    }

    protected function tearDown()
    {
        unset(
            $this->registry
        );
    }

    public function testEnumLabel()
    {
        $enum = $this->prophesize('Yokai\EnumBundle\Enum\EnumInterface');
        $enum->getChoices()
            ->willReturn(['foo' => 'FOO', 'bar' => 'BAR']);

        $this->registry->get('test')
            ->willReturn($enum->reveal());

        $twig = $this->createEnvironment();

        $this->assertSame(
            'FOO',
            $twig->createTemplate("{{ 'foo'|enum_label('test') }}")->render([])
        );
        $this->assertSame(
            'BAR',
            $twig->createTemplate("{{ enum_label('bar', 'test') }}")->render([])
        );

        $this->assertSame(
            'not_exist',
            $twig->createTemplate("{{ 'not_exist'|enum_label('test') }}")->render([])
        );
        $this->assertSame(
            'not_exist',
            $twig->createTemplate("{{ enum_label('not_exist', 'test') }}")->render([])
        );
    }

    public function testEnumChoices()
    {
        $enum = $this->prophesize('Yokai\EnumBundle\Enum\EnumInterface');
        $enum->getChoices()
            ->willReturn(['foo' => 'FOO', 'bar' => 'BAR']);

        $this->registry->get('test')
            ->willReturn($enum->reveal());

        $twig = $this->createEnvironment();

        $this->assertSame(
            'foo,FOO|bar,BAR|',
            $twig->createTemplate("{% for value,label in enum_choices('test') %}{{ value }},{{ label }}|{% endfor %}")->render([])
        );
    }

    /**
     * @return \Twig_Environment
     */
    protected function createEnvironment()
    {
        $loader = new \Twig_Loader_Array([]);
        $twig = new \Twig_Environment($loader, ['debug' => true, 'cache' => false, 'autoescape' => false]);
        $twig->addExtension($this->createExtension());

        return $twig;
    }

    /**
     * @return EnumExtension
     */
    private function createExtension()
    {
        return new EnumExtension($this->registry->reveal());
    }
}

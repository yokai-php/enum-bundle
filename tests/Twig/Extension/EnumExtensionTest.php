<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Twig\Extension;

use Prophecy\Prophecy\ObjectProphecy;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Yokai\EnumBundle\EnumInterface;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Exception\InvalidEnumValueException;
use Yokai\EnumBundle\Tests\TestCase;
use Yokai\EnumBundle\Twig\Extension\EnumExtension;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumExtensionTest extends TestCase
{
    /**
     * @var EnumRegistry|ObjectProphecy
     */
    private $registry;

    protected function setUp(): void
    {
        $this->registry = $this->prophesize(EnumRegistry::class);
    }

    public function testEnumLabel(): void
    {
        $enum = $this->prophesize(EnumInterface::class);
        $enum->getLabel('foo')->willReturn('FOO');
        $enum->getLabel('bar')->willReturn('BAR');
        $enum->getLabel('not_exist')->willThrow(new InvalidEnumValueException());

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

    public function testEnumChoices(): void
    {
        $enum = $this->prophesize(EnumInterface::class);
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
     * @return Environment
     */
    protected function createEnvironment(): Environment
    {
        $loader = new ArrayLoader([]);
        $twig = new Environment($loader, ['debug' => true, 'cache' => false, 'autoescape' => false]);
        $twig->addExtension($this->createExtension());

        return $twig;
    }

    /**
     * @return EnumExtension
     */
    private function createExtension(): EnumExtension
    {
        return new EnumExtension($this->registry->reveal());
    }
}

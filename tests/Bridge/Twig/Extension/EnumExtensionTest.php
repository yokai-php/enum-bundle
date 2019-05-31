<?php declare(strict_types=1);

namespace Yokai\Enum\Tests\Bridge\Symfony\Twig\Extension;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Yokai\Enum\Bridge\Twig\Extension\EnumExtension;
use Yokai\Enum\EnumInterface;
use Yokai\Enum\EnumRegistry;

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

    protected function tearDown(): void
    {
        unset(
            $this->registry
        );
    }

    public function testEnumLabel(): void
    {
        $enum = $this->prophesize(EnumInterface::class);
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
     * @return \Twig_Environment
     */
    protected function createEnvironment(): \Twig_Environment
    {
        $loader = new \Twig_Loader_Array([]);
        $twig = new \Twig_Environment($loader, ['debug' => true, 'cache' => false, 'autoescape' => false]);
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

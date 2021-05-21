<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Twig\Extension;

use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Yokai\EnumBundle\EnumInterface;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Exception\InvalidArgumentException;
use Yokai\EnumBundle\Tests\TestCase;
use Yokai\EnumBundle\Twig\Extension\EnumExtension;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumExtensionTest extends TestCase
{
    public function testEnumLabel(): void
    {
        $enum = $this->prophesize(EnumInterface::class);
        $enum->getName()->willReturn('test');
        $enum->getLabel('foo')->willReturn('FOO');
        $enum->getLabel('bar')->willReturn('BAR');
        $enum->getLabel('not_exist')->willThrow(new InvalidArgumentException());

        $registry = new EnumRegistry();
        $registry->add($enum->reveal());

        $twig = $this->createEnvironment($registry);

        self::assertSame(
            'FOO',
            $twig->createTemplate("{{ 'foo'|enum_label('test') }}")->render([])
        );
        self::assertSame(
            'BAR',
            $twig->createTemplate("{{ 'bar'|enum_label('test') }}")->render([])
        );
    }

    public function testEnumChoices(): void
    {
        $enum = $this->prophesize(EnumInterface::class);
        $enum->getName()->willReturn('test');
        $enum->getChoices()->willReturn(['foo' => 'FOO', 'bar' => 'BAR']);

        $registry = new EnumRegistry();
        $registry->add($enum->reveal());

        $twig = $this->createEnvironment($registry);

        self::assertSame(
            'foo,FOO|bar,BAR|',
            $twig->createTemplate("{% for value,label in enum_choices('test') %}{{ value }},{{ label }}|{% endfor %}")->render([])
        );
    }

    public function testEnumValues(): void
    {
        $enum = $this->prophesize(EnumInterface::class);
        $enum->getName()->willReturn('test');
        $enum->getValues()->willReturn(['foo', 'bar']);

        $registry = new EnumRegistry();
        $registry->add($enum->reveal());

        $twig = $this->createEnvironment($registry);

        self::assertSame(
            'foo|bar|',
            $twig->createTemplate("{% for value in enum_values('test') %}{{ value }}|{% endfor %}")->render([])
        );
    }

    protected function createEnvironment(EnumRegistry $registry): Environment
    {
        $loader = new ArrayLoader([]);
        $twig = new Environment($loader, ['debug' => true, 'cache' => false, 'autoescape' => false]);
        $twig->addExtension(new EnumExtension($registry));

        return $twig;
    }
}

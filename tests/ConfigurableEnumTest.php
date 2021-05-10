<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use Yokai\EnumBundle\ConfigurableEnum;
use Yokai\EnumBundle\Exception\InvalidEnumValueException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConfigurableEnumTest extends TestCase
{
    public function testConfigurability(): void
    {
        $fooEnum = new ConfigurableEnum('foo', ['foo' => 'FOO', 'bar' => 'BAR']);
        self::assertSame('foo', $fooEnum->getName());
        self::assertSame(['foo' => 'FOO', 'bar' => 'BAR'], $fooEnum->getChoices());
        self::assertSame('FOO', $fooEnum->getLabel('foo'));
        self::assertSame('BAR', $fooEnum->getLabel('bar'));
    }

    public function testLabelNotFound(): void
    {
        $this->expectException(InvalidEnumValueException::class);
        $fooEnum = new ConfigurableEnum('foo', ['foo' => 'FOO', 'bar' => 'BAR']);
        $fooEnum->getLabel('unknown enum value');
    }
}

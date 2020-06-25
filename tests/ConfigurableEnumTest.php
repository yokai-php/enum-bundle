<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use Yokai\EnumBundle\ConfigurableEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConfigurableEnumTest extends TestCase
{
    public function testConfigurability(): void
    {
        $fooEnum = new ConfigurableEnum('foo', ['foo' => 'FOO', 'bar' => 'BAR']);
        $this->assertSame('foo', $fooEnum->getName());
        $this->assertSame(['foo' => 'FOO', 'bar' => 'BAR'], $fooEnum->getChoices());
    }
}

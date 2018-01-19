<?php

namespace Yokai\EnumBundle\Tests\Enum;

use Yokai\EnumBundle\Enum\ConfigurableEnum;

class ConfigurableEnumTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigurability()
    {
        $fooEnum = new ConfigurableEnum('foo', ['foo' => 'FOO', 'bar' => 'BAR']);
        $this->assertSame('foo', $fooEnum->getName());
        $this->assertSame(['foo' => 'FOO', 'bar' => 'BAR'], $fooEnum->getChoices());
    }
}

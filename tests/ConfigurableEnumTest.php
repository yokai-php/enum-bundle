<?php

namespace Yokai\Enum\Tests;

use PHPUnit\Framework\TestCase;
use Yokai\Enum\ConfigurableEnum;

class ConfigurableEnumTest extends TestCase
{
    public function testConfigurability()
    {
        $fooEnum = new ConfigurableEnum('foo', ['foo' => 'FOO', 'bar' => 'BAR']);
        $this->assertSame('foo', $fooEnum->getName());
        $this->assertSame(['foo' => 'FOO', 'bar' => 'BAR'], $fooEnum->getChoices());
    }
}

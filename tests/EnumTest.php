<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use DateTimeImmutable;
use LogicException;
use PHPUnit\Framework\TestCase;
use Yokai\EnumBundle\Enum;
use Yokai\EnumBundle\Exception\InvalidArgumentException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumTest extends TestCase
{
    public function testConfigurability(): void
    {
        $fooEnum = new Enum('foo', ['FOO' => 'foo', 'BAR' => 'bar']);
        self::assertSame('foo', $fooEnum->getName());
        self::assertSame(['FOO' => 'foo', 'BAR' => 'bar'], $fooEnum->getChoices());
        self::assertSame(['foo', 'bar'], $fooEnum->getValues());
        self::assertSame('FOO', $fooEnum->getLabel('foo'));
        self::assertSame('BAR', $fooEnum->getLabel('bar'));
    }

    public function testDateEnum(): void
    {
        $enum = new Enum('date', [
            'yesterday' => $yesterday = new DateTimeImmutable('yesterday'),
            'today' => $today = new DateTimeImmutable('now'),
            'tomorrow' => $tomorrow = new DateTimeImmutable('tomorrow'),
        ]);

        self::assertSame('date', $enum->getName());
        self::assertSame(['yesterday' => $yesterday, 'today' => $today, 'tomorrow' => $tomorrow], $enum->getChoices());
        self::assertSame([$yesterday, $today, $tomorrow], $enum->getValues());
        self::assertSame('yesterday', $enum->getLabel($yesterday));
        self::assertSame('tomorrow', $enum->getLabel($tomorrow));
    }

    public function testLabelNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $fooEnum = new Enum('foo', ['foo' => 'FOO', 'bar' => 'BAR']);
        $fooEnum->getLabel('unknown enum value');
    }

    public function testEnumMustHaveChoices(): void
    {
        $this->expectException(LogicException::class);
        new Enum('foo', null);
    }

    public function testInheritedEnumMustHaveChoicesOrBuildMethod(): void
    {
        $this->expectException(LogicException::class);
        $fooEnum = new class ('foo', null) extends Enum {};
        $fooEnum->getLabel('something');
    }
}

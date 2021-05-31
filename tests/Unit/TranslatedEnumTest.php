<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use PHPUnit\Framework\TestCase;
use Yokai\EnumBundle\Exception\InvalidArgumentException;
use Yokai\EnumBundle\Exception\LogicException;
use Yokai\EnumBundle\TranslatedEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class TranslatedEnumTest extends TestCase
{
    public function testConstructedWithInvalidPattern(): void
    {
        $this->expectException(LogicException::class);
        new TranslatedEnum(['foo', 'bar'], new Translator([]), 'invalid.pattern', 'messages', 'invalid');
    }

    public function testTranslatedChoices(): void
    {
        $translator = new Translator([
            'choice.something.foo' => 'FOO translated',
            'choice.something.bar' => 'BAR translated',
        ]);
        $enum = new TranslatedEnum(['foo', 'bar'], $translator, 'choice.something.%s', 'messages', 'something');

        $expectedChoices = [
            'FOO translated' => 'foo',
            'BAR translated' => 'bar',
        ];
        self::assertEquals($expectedChoices, $enum->getChoices());
        self::assertSame(['foo', 'bar'], $enum->getValues());
        self::assertSame('FOO translated', $enum->getLabel('foo'));
        self::assertSame('BAR translated', $enum->getLabel('bar'));
    }

    public function testTranslatedWithDomainChoices(): void
    {
        $translator = new Translator([
            'something.foo' => 'FOO translated',
            'something.bar' => 'BAR translated',
        ], 'choices');
        $enum = new TranslatedEnum(['foo', 'bar'], $translator, 'something.%s', 'choices', 'something');

        $expectedChoices = [
            'FOO translated' => 'foo',
            'BAR translated' => 'bar',
        ];
        self::assertEquals($expectedChoices, $enum->getChoices());
        self::assertSame(['foo', 'bar'], $enum->getValues());
        self::assertSame('FOO translated', $enum->getLabel('foo'));
        self::assertSame('BAR translated', $enum->getLabel('bar'));
    }

    public function testBooleanEnum(): void
    {
        $translator = new Translator([
            'boolean.yes' => 'Oui',
            'boolean.no' => 'Non',
        ]);

        $enum = new TranslatedEnum(['yes' => true, 'no' => false], $translator, 'boolean.%s', 'messages', 'boolean');

        self::assertSame('boolean', $enum->getName());
        self::assertSame(['Oui' => true, 'Non' => false], $enum->getChoices());
        self::assertSame([true, false], $enum->getValues());
        self::assertSame('Oui', $enum->getLabel(true));
        self::assertSame('Non', $enum->getLabel(false));
    }

    public function testDigitEnum(): void
    {
        $translator = new Translator([
            'number.0' => 'Zéro',
            'number.1' => 'Un',
            'number.2' => 'Deux',
            'number.3' => 'Trois',
            'number.4' => 'Quatre',
            'number.5' => 'Cinq',
            'number.6' => 'Six',
            'number.7' => 'Sept',
            'number.8' => 'Huit',
            'number.9' => 'Neuf',
        ], 'math');

        $enum = new TranslatedEnum([0, 1, 2, 3, 4, 5, 6, 7, 8, 9], $translator, 'number.%s', 'math', 'number');

        self::assertSame('number', $enum->getName());
        self::assertSame(
            [
                'Zéro' => 0,
                'Un' => 1,
                'Deux' => 2,
                'Trois' => 3,
                'Quatre' => 4,
                'Cinq' => 5,
                'Six' => 6,
                'Sept' => 7,
                'Huit' => 8,
                'Neuf' => 9,
            ],
            $enum->getChoices()
        );
        self::assertSame([0, 1, 2, 3, 4, 5, 6, 7, 8, 9], $enum->getValues());
        self::assertSame('Trois', $enum->getLabel(3));
        self::assertSame('Neuf', $enum->getLabel(9));
    }

    public function testEnumMustHaveName(): void
    {
        $this->expectException(LogicException::class);
        new TranslatedEnum(['foo', 'bar'], new Translator([]), 'dummy.%s', 'messages', null);
    }

    public function testLabelNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $translator = new Translator([
            'choice.something.foo' => 'FOO translated',
            'choice.something.bar' => 'BAR translated',
        ], 'math');

        $enum = new TranslatedEnum(['foo', 'bar'], $translator, 'choice.something.%s', 'messages', 'something');
        $enum->getLabel('unknown');
    }
}

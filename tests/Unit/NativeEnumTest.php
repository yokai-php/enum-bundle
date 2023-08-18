<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Yokai\EnumBundle\Exception\InvalidArgumentException;
use Yokai\EnumBundle\Exception\LogicException;
use Yokai\EnumBundle\NativeEnum;
use Yokai\EnumBundle\Tests\Unit\Fixtures\HTTPMethod;
use Yokai\EnumBundle\Tests\Unit\Fixtures\HTTPStatus;
use Yokai\EnumBundle\Tests\Unit\Fixtures\Picture;
use Yokai\EnumBundle\Tests\Unit\Fixtures\Vehicle;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class NativeEnumTest extends TestCase
{
    public function testEnum(): void
    {
        if (\PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Only for PHP >= 8.1');
        }

        $enum = new NativeEnum(Picture::class);

        self::assertSame(Picture::class, $enum->getName());
        self::assertSame(['Landscape' => Picture::Landscape, 'Portrait' => Picture::Portrait], $enum->getChoices());
        self::assertSame([Picture::Landscape, Picture::Portrait], $enum->getValues());
        self::assertSame('Landscape', $enum->getLabel(Picture::Landscape));
        self::assertSame('Portrait', $enum->getLabel(Picture::Portrait));
    }

    public function testStringBackedEnum(): void
    {
        if (\PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Only for PHP >= 8.1');
        }

        $enum = new NativeEnum(HTTPMethod::class);

        self::assertSame(HTTPMethod::class, $enum->getName());
        self::assertSame(['GET' => HTTPMethod::GET, 'POST' => HTTPMethod::POST], $enum->getChoices());
        self::assertSame([HTTPMethod::GET, HTTPMethod::POST], $enum->getValues());
        self::assertSame('GET', $enum->getLabel(HTTPMethod::GET));
        self::assertSame('POST', $enum->getLabel(HTTPMethod::POST));
    }

    public function testIntBackedEnum(): void
    {
        if (\PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Only for PHP >= 8.1');
        }

        $enum = new NativeEnum(HTTPStatus::class);

        self::assertSame(HTTPStatus::class, $enum->getName());
        self::assertSame(['OK' => HTTPStatus::OK, 'NOT_FOUND' => HTTPStatus::NOT_FOUND], $enum->getChoices());
        self::assertSame([HTTPStatus::OK, HTTPStatus::NOT_FOUND], $enum->getValues());
        self::assertSame('OK', $enum->getLabel(HTTPStatus::OK));
        self::assertSame('NOT_FOUND', $enum->getLabel(HTTPStatus::NOT_FOUND));
    }

    public function testEnumClassMustBeValid(): void
    {
        if (\PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Only for PHP >= 8.1');
        }

        $this->expectException(LogicException::class);
        new NativeEnum(Vehicle::class);
    }

    public function testLabelNotFound(): void
    {
        if (\PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Only for PHP >= 8.1');
        }

        $this->expectException(InvalidArgumentException::class);
        $enum = new NativeEnum(Picture::class);
        $enum->getLabel('unknown enum value');
    }
}

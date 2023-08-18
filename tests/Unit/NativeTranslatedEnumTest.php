<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Yokai\EnumBundle\Exception\InvalidArgumentException;
use Yokai\EnumBundle\Exception\LogicException;
use Yokai\EnumBundle\MyCLabsEnum;
use Yokai\EnumBundle\NativeEnum;
use Yokai\EnumBundle\NativeTranslatedEnum;
use Yokai\EnumBundle\Tests\Unit\Fixtures\HTTPMethod;
use Yokai\EnumBundle\Tests\Unit\Fixtures\HTTPStatus;
use Yokai\EnumBundle\Tests\Unit\Fixtures\Status;
use Yokai\EnumBundle\Tests\Unit\Fixtures\Picture;
use Yokai\EnumBundle\Tests\Unit\Fixtures\Vehicle;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class NativeTranslatedEnumTest extends TestCase
{
    public function testEnum(): void
    {
        if (\PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Only for PHP >= 8.1');
        }

        $translator = new Translator(['picture.Landscape' => 'Paysage', 'picture.Portrait' => 'Portrait']);
        $enum = new NativeTranslatedEnum(Picture::class, $translator, 'picture.%s');

        self::assertSame(Picture::class, $enum->getName());
        self::assertSame(['Paysage' => Picture::Landscape, 'Portrait' => Picture::Portrait], $enum->getChoices());
        self::assertSame([Picture::Landscape, Picture::Portrait], $enum->getValues());
        self::assertSame('Paysage', $enum->getLabel(Picture::Landscape));
        self::assertSame('Portrait', $enum->getLabel(Picture::Portrait));
    }

    public function testStringBackedEnum(): void
    {
        if (\PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Only for PHP >= 8.1');
        }

        $translator = new Translator(['method.GET' => 'get', 'method.POST' => 'post']);
        $enum = new NativeTranslatedEnum(HTTPMethod::class, $translator, 'method.%s');

        self::assertSame(HTTPMethod::class, $enum->getName());
        self::assertSame(['get' => HTTPMethod::GET, 'post' => HTTPMethod::POST], $enum->getChoices());
        self::assertSame([HTTPMethod::GET, HTTPMethod::POST], $enum->getValues());
        self::assertSame('get', $enum->getLabel(HTTPMethod::GET));
        self::assertSame('post', $enum->getLabel(HTTPMethod::POST));
    }

    public function testIntBackedEnum(): void
    {
        if (\PHP_VERSION_ID < 80100) {
            self::markTestSkipped('Only for PHP >= 8.1');
        }

        $translator = new Translator(['status.OK' => 'OK', 'status.NOT_FOUND' => 'Introuvable']);
        $enum = new NativeTranslatedEnum(HTTPStatus::class, $translator, 'status.%s');

        self::assertSame(HTTPStatus::class, $enum->getName());
        self::assertSame(['OK' => HTTPStatus::OK, 'Introuvable' => HTTPStatus::NOT_FOUND], $enum->getChoices());
        self::assertSame([HTTPStatus::OK, HTTPStatus::NOT_FOUND], $enum->getValues());
        self::assertSame('OK', $enum->getLabel(HTTPStatus::OK));
        self::assertSame('Introuvable', $enum->getLabel(HTTPStatus::NOT_FOUND));
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

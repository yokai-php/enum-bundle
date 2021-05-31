<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Yokai\EnumBundle\Exception\InvalidArgumentException;
use Yokai\EnumBundle\Exception\LogicException;
use Yokai\EnumBundle\MyCLabsEnum;
use Yokai\EnumBundle\Tests\Unit\Fixtures\Status;
use Yokai\EnumBundle\Tests\Unit\Fixtures\Vehicle;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class MyCLabsEnumTest extends TestCase
{
    public function testEnum(): void
    {
        $enum = new MyCLabsEnum(Status::class);

        self::assertSame(Status::class, $enum->getName());
        self::assertEquals(['SUCCESS' => Status::SUCCESS(), 'ERROR' => Status::ERROR()], $enum->getChoices());
        self::assertEquals([Status::SUCCESS(), Status::ERROR()], $enum->getValues());
        self::assertSame('SUCCESS', $enum->getLabel(Status::SUCCESS()));
        self::assertSame('ERROR', $enum->getLabel(Status::ERROR()));
    }

    public function testEnumClassMustBeValid(): void
    {
        $this->expectException(LogicException::class);
        new MyCLabsEnum(Vehicle::class);
    }

    public function testLabelNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $enum = new MyCLabsEnum(Status::class);
        $enum->getLabel('unknown enum value');
    }
}

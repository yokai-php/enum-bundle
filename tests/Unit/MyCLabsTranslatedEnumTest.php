<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Yokai\EnumBundle\Exception\InvalidArgumentException;
use Yokai\EnumBundle\Exception\LogicException;
use Yokai\EnumBundle\MyCLabsTranslatedEnum;
use Yokai\EnumBundle\Tests\Unit\Fixtures\Action;
use Yokai\EnumBundle\Tests\Unit\Fixtures\Vehicle;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class MyCLabsTranslatedEnumTest extends TestCase
{
    public function testEnum(): void
    {
        $translator = new Translator(['action.VIEW' => 'Voir', 'action.EDIT' => 'Modifier']);
        $enum = new MyCLabsTranslatedEnum(Action::class, $translator, 'action.%s');

        self::assertSame(Action::class, $enum->getName());
        self::assertEquals(['Voir' => Action::VIEW(), 'Modifier' => Action::EDIT()], $enum->getChoices());
        self::assertEquals([Action::VIEW(), Action::EDIT()], $enum->getValues());
        self::assertSame('Voir', $enum->getLabel(Action::VIEW()));
        self::assertSame('Modifier', $enum->getLabel(Action::EDIT()));
    }

    public function testEnumClassMustBeValid(): void
    {
        $this->expectException(LogicException::class);
        new MyCLabsTranslatedEnum(Vehicle::class, new Translator([]), 'dummy.%s');
    }

    public function testLabelNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $translator = new Translator(['action.VIEW' => 'Voir', 'action.EDIT' => 'Modifier']);
        $enum = new MyCLabsTranslatedEnum(Action::class, $translator, 'action.%s');
        $enum->getLabel('unknown enum value');
    }
}

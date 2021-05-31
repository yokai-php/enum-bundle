<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\ConstantListTranslatedEnum;
use Yokai\EnumBundle\Exception\InvalidArgumentException;
use Yokai\EnumBundle\Exception\LogicException;
use Yokai\EnumBundle\Tests\Fixtures\Vehicle;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConstantListTranslatedEnumTest extends TestCase
{
    public function getEnum(string $pattern, string $name, TranslatorInterface $translator): ConstantListTranslatedEnum
    {
        return new ConstantListTranslatedEnum($pattern, $translator, $name . '.%s', 'messages', $name);
    }

    public function testVehicleEnums(): void
    {
        $translator = new Translator([
            'vehicle.type.bike' => 'Moto',
            'vehicle.type.car' => 'Voiture',
            'vehicle.type.bus' => 'Bus',
            'vehicle.engine.electic' => 'Electrique',
            'vehicle.engine.combustion' => 'Combustion',
            'vehicle.brand.renault' => 'Renault',
            'vehicle.brand.volkswagen' => 'Volkswagen',
            'vehicle.brand.toyota' => 'Toyota',
        ]);

        $type = $this->getEnum(Vehicle::class . '::TYPE_*', 'vehicle.type', $translator);
        self::assertSame('vehicle.type', $type->getName());
        self::assertSame(
            ['Moto' => 'bike', 'Voiture' => 'car', 'Bus' => 'bus'],
            $type->getChoices()
        );
        self::assertSame(['bike', 'car', 'bus'], $type->getValues());
        self::assertSame('Moto', $type->getLabel('bike'));
        self::assertSame('Bus', $type->getLabel('bus'));

        $engine = $this->getEnum(Vehicle::class . '::ENGINE_*', 'vehicle.engine', $translator);
        self::assertSame('vehicle.engine', $engine->getName());
        self::assertSame(
            ['Electrique' => 'electic', 'Combustion' => 'combustion'],
            $engine->getChoices()
        );
        self::assertSame(['electic', 'combustion'], $engine->getValues());
        self::assertSame('Electrique', $engine->getLabel('electic'));
        self::assertSame('Combustion', $engine->getLabel('combustion'));

        $brand = $this->getEnum(Vehicle::class . '::BRAND_*', 'vehicle.brand', $translator);
        self::assertSame('vehicle.brand', $brand->getName());
        self::assertSame(
            ['Renault' => 'renault', 'Volkswagen' => 'volkswagen', 'Toyota' => 'toyota'],
            $brand->getChoices()
        );
        self::assertSame(['renault', 'volkswagen', 'toyota'], $brand->getValues());
        self::assertSame('Renault', $brand->getLabel('renault'));
        self::assertSame('Toyota', $brand->getLabel('toyota'));
    }

    public function testEnumMustHaveName(): void
    {
        $this->expectException(LogicException::class);
        new ConstantListTranslatedEnum(Vehicle::class . '::TYPE_*', new Translator([]), 'vehicle.type.%s');
    }

    public function testLabelNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $translator = new Translator([
            'vehicle.type.bike' => 'Moto',
            'vehicle.type.car' => 'Voiture',
            'vehicle.type.bus' => 'Bus',
        ]);

        $enum = $this->getEnum(Vehicle::class . '::TYPE_*', 'vehicle.type', $translator);

        $enum->getLabel('unknown');
    }
}

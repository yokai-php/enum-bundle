<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use Yokai\EnumBundle\ConstantListEnum;
use Yokai\EnumBundle\Exception\InvalidEnumValueException;
use Yokai\EnumBundle\Tests\Fixtures\Vehicle;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConstantListEnumTest extends TestCase
{
    public function getEnum(string $pattern, string $name): ConstantListEnum
    {
        return new ConstantListEnum($pattern, $name);
    }

    public function testVehicleEnums(): void
    {
        $type = $this->getEnum(Vehicle::class.'::TYPE_*', 'vehicle.type');
        self::assertSame('vehicle.type', $type->getName());
        self::assertSame(
            ['bike' => 'bike', 'car' => 'car', 'bus' => 'bus'],
            $type->getChoices()
        );
        self::assertSame(['bike', 'car', 'bus'], $type->getValues());
        self::assertSame('bike', $type->getLabel('bike'));
        self::assertSame('bus', $type->getLabel('bus'));

        $engine = $this->getEnum(Vehicle::class.'::ENGINE_*', 'vehicle.engine');
        self::assertSame('vehicle.engine', $engine->getName());
        self::assertSame(
            ['electic' => 'electic', 'combustion' => 'combustion'],
            $engine->getChoices()
        );
        self::assertSame(['electic', 'combustion'], $engine->getValues());
        self::assertSame('electic', $engine->getLabel('electic'));
        self::assertSame('combustion', $engine->getLabel('combustion'));

        $brand = $this->getEnum(Vehicle::class.'::BRAND_*', 'vehicle.brand');
        self::assertSame('vehicle.brand', $brand->getName());
        self::assertSame(
            ['renault' => 'renault', 'volkswagen' => 'volkswagen', 'toyota' => 'toyota'],
            $brand->getChoices()
        );
        self::assertSame(['renault', 'volkswagen', 'toyota'], $brand->getValues());
        self::assertSame('renault', $brand->getLabel('renault'));
        self::assertSame('toyota', $brand->getLabel('toyota'));
    }

    public function testLabelNotFound(): void
    {
        $this->expectException(InvalidEnumValueException::class);

        $enum = $this->getEnum(Vehicle::class.'::TYPE_*', 'vehicle.type');

        $enum->getLabel('unknown');
    }
}

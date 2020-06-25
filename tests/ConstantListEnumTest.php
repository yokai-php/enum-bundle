<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use PHPUnit\Framework\TestCase;
use Yokai\EnumBundle\ConstantExtractor;
use Yokai\EnumBundle\ConstantListEnum;
use Yokai\EnumBundle\Tests\Fixtures\Vehicle;

class ConstantListEnumTest extends TestCase
{
    public function getEnum(string $pattern, string $name): ConstantListEnum
    {
        return new ConstantListEnum(new ConstantExtractor(), $pattern, $name);
    }

    public function testVehicleEnums(): void
    {
        $type = $this->getEnum(Vehicle::class.'::TYPE_*', 'vehicle.type');
        self::assertSame('vehicle.type', $type->getName());
        self::assertSame(
            ['bike' => 'bike', 'car' => 'car', 'bus' => 'bus'],
            $type->getChoices()
        );

        $engine = $this->getEnum(Vehicle::class.'::ENGINE_*', 'vehicle.engine');
        self::assertSame('vehicle.engine', $engine->getName());
        self::assertSame(
            ['electic' => 'electic', 'combustion' => 'combustion'],
            $engine->getChoices()
        );

        $brand = $this->getEnum(Vehicle::class.'::BRAND_*', 'vehicle.brand');
        self::assertSame('vehicle.brand', $brand->getName());
        self::assertSame(
            ['renault' => 'renault', 'volkswagen' => 'volkswagen', 'toyota' => 'toyota'],
            $brand->getChoices()
        );
    }
}

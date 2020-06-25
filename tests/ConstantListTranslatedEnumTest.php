<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\ConstantExtractor;
use Yokai\EnumBundle\ConstantListTranslatedEnum;
use Yokai\EnumBundle\Tests\Fixtures\Vehicle;

class ConstantListTranslatedEnumTest extends TestCase
{
    /**
     * @var TranslatorInterface|ObjectProphecy
     */
    private $translator;

    protected function setUp(): void
    {
        $this->translator = $this->prophesize(TranslatorInterface::class);
    }

    protected function tearDown(): void
    {
        $this->translator = null;
    }

    public function getEnum(string $pattern, string $name): ConstantListTranslatedEnum
    {
        return new ConstantListTranslatedEnum(
            new ConstantExtractor(),
            $pattern,
            $this->translator->reveal(),
            $name.'.%s',
            $name
        );
    }

    public function testVehicleEnums(): void
    {
        $type = $this->getEnum(Vehicle::class.'::TYPE_*', 'vehicle.type');
        $this->translator->trans('vehicle.type.bike', [], 'messages')->shouldBeCalledTimes(1)->willReturn('Moto');
        $this->translator->trans('vehicle.type.car', [], 'messages')->shouldBeCalledTimes(1)->willReturn('Voiture');
        $this->translator->trans('vehicle.type.bus', [], 'messages')->shouldBeCalledTimes(1)->willReturn('Bus');
        self::assertSame('vehicle.type', $type->getName());
        self::assertSame(
            ['bike' => 'Moto', 'car' => 'Voiture', 'bus' => 'Bus'],
            $type->getChoices()
        );

        $engine = $this->getEnum(Vehicle::class.'::ENGINE_*', 'vehicle.engine');
        $this->translator->trans('vehicle.engine.electic', [], 'messages')->shouldBeCalledTimes(1)->willReturn('Electrique');
        $this->translator->trans('vehicle.engine.combustion', [], 'messages')->shouldBeCalledTimes(1)->willReturn('Combustion');
        self::assertSame('vehicle.engine', $engine->getName());
        self::assertSame(
            ['electic' => 'Electrique', 'combustion' => 'Combustion'],
            $engine->getChoices()
        );

        $brand = $this->getEnum(Vehicle::class.'::BRAND_*', 'vehicle.brand');
        $this->translator->trans('vehicle.brand.renault', [], 'messages')->shouldBeCalledTimes(1)->willReturn('Renault');
        $this->translator->trans('vehicle.brand.volkswagen', [], 'messages')->shouldBeCalledTimes(1)->willReturn('Volkswagen');
        $this->translator->trans('vehicle.brand.toyota', [], 'messages')->shouldBeCalledTimes(1)->willReturn('Toyota');
        self::assertSame('vehicle.brand', $brand->getName());
        self::assertSame(
            ['renault' => 'Renault', 'volkswagen' => 'Volkswagen', 'toyota' => 'Toyota'],
            $brand->getChoices()
        );
    }
}

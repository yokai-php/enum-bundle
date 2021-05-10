<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\ConstantListTranslatedEnum;
use Yokai\EnumBundle\Exception\InvalidEnumValueException;
use Yokai\EnumBundle\Tests\Fixtures\Vehicle;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
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

    public function getEnum(string $pattern, string $name): ConstantListTranslatedEnum
    {
        return new ConstantListTranslatedEnum(
            $pattern,
            $this->translator->reveal(),
            $name.'.%s',
            $name
        );
    }

    public function testVehicleEnums(): void
    {
        $type = $this->getEnum(Vehicle::class.'::TYPE_*', 'vehicle.type');
        $this->translator->trans('vehicle.type.bike', [], 'messages')->shouldBeCalled()->willReturn('Moto');
        $this->translator->trans('vehicle.type.car', [], 'messages')->shouldBeCalled()->willReturn('Voiture');
        $this->translator->trans('vehicle.type.bus', [], 'messages')->shouldBeCalled()->willReturn('Bus');
        self::assertSame('vehicle.type', $type->getName());
        self::assertSame(
            ['bike' => 'Moto', 'car' => 'Voiture', 'bus' => 'Bus'],
            $type->getChoices()
        );
        self::assertSame('Moto', $type->getLabel('bike'));
        self::assertSame('Bus', $type->getLabel('bus'));

        $engine = $this->getEnum(Vehicle::class.'::ENGINE_*', 'vehicle.engine');
        $this->translator->trans('vehicle.engine.electic', [], 'messages')->shouldBeCalled()->willReturn('Electrique');
        $this->translator->trans('vehicle.engine.combustion', [], 'messages')->shouldBeCalled()->willReturn('Combustion');
        self::assertSame('vehicle.engine', $engine->getName());
        self::assertSame(
            ['electic' => 'Electrique', 'combustion' => 'Combustion'],
            $engine->getChoices()
        );
        self::assertSame('Electrique', $engine->getLabel('electic'));
        self::assertSame('Combustion', $engine->getLabel('combustion'));

        $brand = $this->getEnum(Vehicle::class.'::BRAND_*', 'vehicle.brand');
        $this->translator->trans('vehicle.brand.renault', [], 'messages')->shouldBeCalled()->willReturn('Renault');
        $this->translator->trans('vehicle.brand.volkswagen', [], 'messages')->shouldBeCalled()->willReturn('Volkswagen');
        $this->translator->trans('vehicle.brand.toyota', [], 'messages')->shouldBeCalled()->willReturn('Toyota');
        self::assertSame('vehicle.brand', $brand->getName());
        self::assertSame(
            ['renault' => 'Renault', 'volkswagen' => 'Volkswagen', 'toyota' => 'Toyota'],
            $brand->getChoices()
        );
        self::assertSame('Renault', $brand->getLabel('renault'));
        self::assertSame('Toyota', $brand->getLabel('toyota'));
    }

    public function testLabelNotFound(): void
    {
        $this->expectException(InvalidEnumValueException::class);

        $enum = $this->getEnum(Vehicle::class.'::TYPE_*', 'vehicle.type');
        $this->translator->trans('vehicle.type.bike', [], 'messages')->shouldBeCalled()->willReturn('Moto');
        $this->translator->trans('vehicle.type.car', [], 'messages')->shouldBeCalled()->willReturn('Voiture');
        $this->translator->trans('vehicle.type.bus', [], 'messages')->shouldBeCalled()->willReturn('Bus');

        $enum->getLabel('unknown');
    }
}

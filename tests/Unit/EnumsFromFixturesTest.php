<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use Generator;
use PHPUnit\Framework\TestCase;
use Yokai\EnumBundle\EnumInterface;
use Yokai\EnumBundle\Tests\Fixtures\Action;
use Yokai\EnumBundle\Tests\Fixtures\ActionEnum;
use Yokai\EnumBundle\Tests\Fixtures\StateEnum;
use Yokai\EnumBundle\Tests\Fixtures\Status;
use Yokai\EnumBundle\Tests\Fixtures\StatusEnum;
use Yokai\EnumBundle\Tests\Fixtures\SubscriptionEnum;
use Yokai\EnumBundle\Tests\Fixtures\TypeEnum;
use Yokai\EnumBundle\Tests\Fixtures\Vehicle;
use Yokai\EnumBundle\Tests\Fixtures\VehicleBrandEnum;
use Yokai\EnumBundle\Tests\Fixtures\VehicleEngineEnum;
use Yokai\EnumBundle\Tests\Fixtures\VehicleTypeEnum;
use Yokai\EnumBundle\Tests\Fixtures\VehicleWheelsEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumsFromFixturesTest extends TestCase
{
    /**
     * @dataProvider enums
     */
    public function testEnum(EnumInterface $enum, string $name, array $choices): void
    {
        self::assertSame($name, $enum->getName());
        self::assertEquals($choices, $enum->getChoices());
        self::assertEquals(\array_values($choices), $enum->getValues());
        foreach ($choices as $label => $value) {
            self::assertSame($label, $enum->getLabel($value));
        }
    }

    public function enums(): Generator
    {
        yield ActionEnum::class . ' : myclabs/php-enum translated enum' => [
            new ActionEnum(new Translator([
                'action.VIEW' => 'Voir',
                'action.EDIT' => 'Modifier',
            ])),
            ActionEnum::class,
            ['Voir' => Action::VIEW(), 'Modifier' => Action::EDIT()]
        ];

        yield StateEnum::class . ' : direct interface implementation' => [
            new StateEnum(),
            StateEnum::class,
            ['New' => 'new', 'Validated' => 'validated', 'Disabled' => 'disabled']
        ];

        yield StatusEnum::class . ' : myclabs/php-enum enum' => [
            new StatusEnum(),
            StatusEnum::class,
            ['SUCCESS' => Status::SUCCESS(), 'ERROR' => Status::ERROR()]
        ];

        yield SubscriptionEnum::class . ' : translated enum with keyword name' => [
            new SubscriptionEnum(new Translator([
                'choice.subscription.none' => 'Aucune',
                'choice.subscription.daily' => 'Journalière',
                'choice.subscription.weekly' => 'Hebdomadaire',
                'choice.subscription.monthly' => 'Mensuelle',
            ])),
            'subscription',
            ['Aucune' => 'none', 'Journalière' => 'daily', 'Hebdomadaire' => 'weekly', 'Mensuelle' => 'monthly']
        ];

        yield TypeEnum::class . ' : enum with keyword name' => [
            new TypeEnum(),
            'type',
            ['Customer' => 'customer', 'Prospect' => 'prospect']
        ];

        yield VehicleBrandEnum::class . ' : constant list enum with keyword name' => [
            new VehicleBrandEnum(),
            'vehicle.brand',
            [
                Vehicle::BRAND_RENAULT => Vehicle::BRAND_RENAULT,
                Vehicle::BRAND_VOLKSWAGEN => Vehicle::BRAND_VOLKSWAGEN,
                Vehicle::BRAND_TOYOTA => Vehicle::BRAND_TOYOTA,
            ],
        ];

        yield VehicleEngineEnum::class . ' : translated constant list enum with keyword name' => [
            new VehicleEngineEnum(new Translator([
                'vehicle.engine.electic' => 'Électrique',
                'vehicle.engine.combustion' => 'Combustion',
            ])),
            'vehicle.engine',
            [
                'Électrique' => Vehicle::ENGINE_ELECTRIC,
                'Combustion' => Vehicle::ENGINE_COMBUSTION,
            ],
        ];

        yield VehicleTypeEnum::class . ' : translated constant list enum with class name' => [
            new VehicleTypeEnum(new Translator([
                'vehicle.type.bike' => 'Vélo',
                'vehicle.type.car' => 'Voiture',
                'vehicle.type.bus' => 'Bus',
            ])),
            VehicleTypeEnum::class,
            [
                'Vélo' => Vehicle::TYPE_BIKE,
                'Voiture' => Vehicle::TYPE_CAR,
                'Bus' => Vehicle::TYPE_BUS,
            ],
        ];

        yield VehicleWheelsEnum::class . ' : translated integer enum with class name' => [
            new VehicleWheelsEnum(new Translator([
                'vehicle.wheels.two' => 'Deux',
                'vehicle.wheels.four' => 'Quatre',
                'vehicle.wheels.eight' => 'Huit',
            ])),
            VehicleWheelsEnum::class,
            [
                'Deux' => Vehicle::WHEELS_TWO,
                'Quatre' => Vehicle::WHEELS_FOUR,
                'Huit' => Vehicle::WHEELS_EIGHT,
            ],
        ];
    }
}

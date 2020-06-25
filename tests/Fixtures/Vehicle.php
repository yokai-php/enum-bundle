<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Fixtures;

class Vehicle
{
    public const TYPE_BIKE = 'bike';
    public const TYPE_CAR = 'car';
    public const TYPE_BUS = 'bus';

    public const ENGINE_ELECTRIC = 'electic';
    public const ENGINE_COMBUSTION = 'combustion';

    public const BRAND_RENAULT = 'renault';
    public const BRAND_VOLKSWAGEN = 'volkswagen';
    public const BRAND_TOYOTA = 'toyota';
}

<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit\Fixtures;

/**
 * Constant holder for all Vehicle*Enum classes.
 *
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
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

    public const WHEELS_TWO = 2;
    public const WHEELS_FOUR = 4;
    public const WHEELS_EIGHT = 8;
}

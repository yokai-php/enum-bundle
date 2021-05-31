<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit\Fixtures;

use Yokai\EnumBundle\ConstantListEnum;

/**
 * Inherit implementation from base class.
 * Values are extracted from `Vehicle` class constants.
 * Each value label is equal to constant value.
 *
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class VehicleBrandEnum extends ConstantListEnum
{
    public function __construct()
    {
        parent::__construct(Vehicle::class . '::BRAND_*', 'vehicle.brand');
    }
}

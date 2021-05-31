<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Fixtures;

use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\TranslatedEnum;

/**
 * Inherit implementation from base class.
 * Values are hardcoded as a constructor argument.
 * Each value label is built and translated by `TranslatedEnum` base class.
 *
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class VehicleWheelsEnum extends TranslatedEnum
{
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct(
            ['two' => Vehicle::WHEELS_TWO, 'four' => Vehicle::WHEELS_FOUR, 'eight' => Vehicle::WHEELS_EIGHT],
            $translator,
            'vehicle.wheels.%s'
        );
    }
}

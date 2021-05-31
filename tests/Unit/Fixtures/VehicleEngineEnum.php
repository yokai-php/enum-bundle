<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit\Fixtures;

use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\ConstantListTranslatedEnum;

/**
 * Inherit implementation from base class.
 * Values are extracted from `Vehicle` class constants.
 * Each value label is built and translated by `TranslatedEnum` base class.
 *
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class VehicleEngineEnum extends ConstantListTranslatedEnum
{
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct(
            Vehicle::class . '::ENGINE_*',
            $translator,
            'vehicle.engine.%s',
            'messages',
            'vehicle.engine'
        );
    }
}

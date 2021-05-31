<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit\Fixtures;

use Yokai\EnumBundle\MyCLabsEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class StatusEnum extends MyCLabsEnum
{
    public function __construct()
    {
        parent::__construct(Status::class);
    }
}

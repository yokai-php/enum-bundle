<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Integration\App\Model;

use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestLabelEnum;
use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestMyCLabsStatusEnum;
use Yokai\EnumBundle\Validator\Constraints\Enum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class PullRequestPhp7
{
    /**
     * @var MyCLabsStatus
     *
     * @Enum(PullRequestMyCLabsStatusEnum::class)
     */
    public $status;

    /**
     * @var string[]
     *
     * @Enum(PullRequestLabelEnum::class, multiple=true)
     */
    public $labels;
}

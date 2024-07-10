<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Integration\App\Model;

use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestLabelEnum;
use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestMyCLabsStatusEnum;
use Yokai\EnumBundle\Validator\Constraints\Enum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class PullRequestPhp80
{
    /**
     * @var MyCLabsStatus
     */
    #[Enum(enum: PullRequestMyCLabsStatusEnum::class)]
    public $status;

    /**
     * @var string[]
     */
    #[Enum(enum: PullRequestLabelEnum::class, multiple: true)]
    public $labels;
}

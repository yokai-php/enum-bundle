<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Integration\App\Model;

use Yokai\EnumBundle\Validator\Constraints\Enum;
use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestStatusEnum;
use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestLabelEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class PullRequestUsingAttributes
{
    /**
     * @var Status
     */
    #[Enum(enum: PullRequestStatusEnum::class)]
    public $status;

    /**
     * @var string[]
     */
    #[Enum(enum: PullRequestLabelEnum::class, multiple: true)]
    public $labels;
}

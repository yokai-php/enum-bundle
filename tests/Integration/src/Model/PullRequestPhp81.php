<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Integration\App\Model;

use Yokai\EnumBundle\Validator\Constraints\Enum;
use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestNativeStatusEnum;
use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestLabelEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class PullRequestPhp81
{
    /**
     * @var NativeStatus
     */
    #[Enum(enum: PullRequestNativeStatusEnum::class)]
    public $status;

    /**
     * @var string[]
     */
    #[Enum(enum: PullRequestLabelEnum::class, multiple: true)]
    public $labels;
}

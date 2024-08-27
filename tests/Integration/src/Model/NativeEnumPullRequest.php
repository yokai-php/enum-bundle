<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Integration\App\Model;

use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestLabelEnum;
use Yokai\EnumBundle\Tests\Integration\App\Enum\PullRequestNativeStatusEnum;
use Yokai\EnumBundle\Validator\Constraints\Enum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class NativeEnumPullRequest
{
    #[Enum(enum: PullRequestNativeStatusEnum::class)]
    public NativeStatus $status;

    /**
     * @var string[]
     */
    #[Enum(enum: PullRequestLabelEnum::class, multiple: true)]
    public array $labels;
}

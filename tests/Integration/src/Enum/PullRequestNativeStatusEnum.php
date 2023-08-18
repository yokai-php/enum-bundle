<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Integration\App\Enum;

use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\NativeTranslatedEnum;
use Yokai\EnumBundle\Tests\Integration\App\Model\NativeStatus;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class PullRequestNativeStatusEnum extends NativeTranslatedEnum
{
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct(NativeStatus::class, $translator, 'pull_request.status.%s');
    }
}

<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Integration\App\Enum;

use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\MyCLabsTranslatedEnum;
use Yokai\EnumBundle\Tests\Integration\App\Model\Status;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class PullRequestStatusEnum extends MyCLabsTranslatedEnum
{
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct(Status::class, $translator, 'pull_request.status.%s');
    }
}

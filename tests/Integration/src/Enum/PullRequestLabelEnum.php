<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Integration\App\Enum;

use Yokai\EnumBundle\Enum;

final class PullRequestLabelEnum extends Enum
{
    private const LABELS = ['feature', 'bugfix', 'hotfix', '1.x', '2.x', '3.x'];

    public function __construct()
    {
        parent::__construct(\array_combine(self::LABELS, self::LABELS));
    }
}

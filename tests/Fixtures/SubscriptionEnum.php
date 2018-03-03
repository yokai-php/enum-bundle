<?php

namespace Yokai\Enum\Tests\Fixtures;

use Symfony\Component\Translation\TranslatorInterface;
use Yokai\Enum\ConfigurableTranslatedEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class SubscriptionEnum extends ConfigurableTranslatedEnum
{
    /**
     * @inheritDoc
     */
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct(
            $translator,
            'choice.subscription.%s',
            'subscription',
            ['none', 'daily', 'weekly', 'monthly']
        );
    }
}

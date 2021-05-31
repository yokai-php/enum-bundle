<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Fixtures;

use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\TranslatedEnum;

/**
 * Inherit implementation from base class.
 * Values are hardcoded as a constructor argument.
 * Each value label is built and translated by `TranslatedEnum` base class.
 *
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class SubscriptionEnum extends TranslatedEnum
{
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct(
            ['none', 'daily', 'weekly', 'monthly'],
            $translator,
            'choice.subscription.%s',
            'messages',
            'subscription'
        );
    }
}

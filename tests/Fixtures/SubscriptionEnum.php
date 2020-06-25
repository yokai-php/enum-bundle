<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Fixtures;

use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\ConfigurableTranslatedEnum;

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

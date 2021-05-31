<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Fixtures;

use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\MyCLabsTranslatedEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ActionEnum extends MyCLabsTranslatedEnum
{
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct(Action::class, $translator, 'action.%s');
    }
}

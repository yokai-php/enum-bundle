<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Fixtures;

use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\TranslatedEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class StateEnum extends TranslatedEnum
{
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct('state', ['new', 'validated', 'disabled'], $translator, 'choice.state.%s');
    }
}

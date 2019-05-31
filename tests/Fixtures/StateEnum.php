<?php

namespace Yokai\Enum\Tests\Fixtures;

use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\Enum\AbstractTranslatedEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class StateEnum extends AbstractTranslatedEnum
{
    /**
     * @inheritDoc
     */
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct($translator, 'choice.state.%s');
    }

    protected function getValues()
    {
        return ['new', 'validated', 'disabled'];
    }

    public function getName()
    {
        return 'state';
    }
}

<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Fixtures;

use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\AbstractTranslatedEnum;

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

    protected function getValues(): array
    {
        return ['new', 'validated', 'disabled'];
    }

    public function getName(): string
    {
        return 'state';
    }
}

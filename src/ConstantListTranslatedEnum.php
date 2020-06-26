<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConstantListTranslatedEnum extends ConfigurableTranslatedEnum
{
    /**
     * @inheritDoc
     */
    public function __construct(
        string $constantsPattern,
        TranslatorInterface $translator,
        string $transPattern,
        string $name
    ) {
        parent::__construct($translator, $transPattern, $name, ConstantExtractor::extract($constantsPattern));
    }
}

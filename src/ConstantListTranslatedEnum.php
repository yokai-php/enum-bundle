<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConstantListTranslatedEnum extends TranslatedEnum
{
    public function __construct(
        string $constantsPattern,
        TranslatorInterface $translator,
        string $transPattern,
        string $transDomain = 'messages',
        ?string $name = null
    ) {
        parent::__construct(
            ConstantExtractor::extract($constantsPattern),
            $translator,
            $transPattern,
            $transDomain,
            $name
        );
    }
}

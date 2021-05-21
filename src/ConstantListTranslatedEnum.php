<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConstantListTranslatedEnum extends TranslatedEnum
{
    /**
     * @param string              $name
     * @param string              $constantsPattern
     * @param TranslatorInterface $translator
     * @param string              $transPattern
     * @param string              $transDomain
     */
    public function __construct(
        string $name,
        string $constantsPattern,
        TranslatorInterface $translator,
        string $transPattern,
        string $transDomain = 'messages'
    ) {
        parent::__construct(
            $name,
            ConstantExtractor::extract($constantsPattern),
            $translator,
            $transPattern,
            $transDomain
        );
    }
}

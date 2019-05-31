<?php declare(strict_types=1);

namespace Yokai\Enum;

use Symfony\Contracts\Translation\TranslatorInterface;

class ConstantListTranslatedEnum extends ConfigurableTranslatedEnum
{
    /**
     * @inheritDoc
     */
    public function __construct(
        ConstantExtractor $extractor,
        string $constantsPattern,
        TranslatorInterface $translator,
        string $transPattern,
        string $name
    ) {
        parent::__construct($translator, $transPattern, $name, $extractor->extract($constantsPattern));
    }
}

<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use MyCLabs\Enum\Enum as ActualMyCLabsEnum;
use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\Exception\LogicException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class MyCLabsTranslatedEnum extends TranslatedEnum
{
    /**
     * @param string              $enum
     * @param TranslatorInterface $translator
     * @param string              $transPattern
     * @param string              $transDomain
     * @param string|null         $name
     */
    public function __construct(
        string $enum,
        TranslatorInterface $translator,
        string $transPattern,
        string $transDomain = 'messages',
        string $name = null
    ) {
        if (!\is_a($enum, ActualMyCLabsEnum::class, true)) {
            throw LogicException::invalidMyClabsEnumClass($enum);
        }

        if ($name === null && static::class === __CLASS__) {
            $name = $enum;
        }

        parent::__construct($enum::values(), $translator, $transPattern, $transDomain, $name);
    }
}

<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use Symfony\Contracts\Translation\TranslatorInterface;
use UnitEnum;
use Yokai\EnumBundle\Exception\LogicException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class NativeTranslatedEnum extends TranslatedEnum
{
    public function __construct(
        string $enum,
        TranslatorInterface $translator,
        string $transPattern,
        string $transDomain = 'messages',
        string|null $name = null
    ) {
        if (!\is_a($enum, UnitEnum::class, true)) {
            throw LogicException::invalidUnitEnum($enum);
        }

        if ($name === null && static::class === __CLASS__) {
            $name = $enum;
        }

        $values = [];
        foreach ($enum::cases() as $case) {
            $values[$case->name] = $case;
        }

        parent::__construct($values, $translator, $transPattern, $transDomain, $name);
    }
}

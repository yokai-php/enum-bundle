<?php

namespace Yokai\Enum\Exception;

use InvalidArgumentException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class InvalidTranslatePatternException extends InvalidArgumentException
{
    /**
     * @param string $transPattern
     *
     * @return InvalidTranslatePatternException
     */
    public static function placeholderRequired($transPattern)
    {
        return new self(sprintf('Translation pattern "%s" must contain %%s placeholder', $transPattern));
    }
}

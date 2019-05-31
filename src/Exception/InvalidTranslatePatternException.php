<?php declare(strict_types=1);

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
    public static function placeholderRequired(string $transPattern): self
    {
        return new self(sprintf('Translation pattern "%s" must contain %%s placeholder', $transPattern));
    }
}

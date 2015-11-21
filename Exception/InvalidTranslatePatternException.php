<?php

namespace EnumBundle\Exception;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class InvalidTranslatePatternException extends \InvalidArgumentException
{
    /**
     * @param string $transPattern
     * @return InvalidTranslatePatternException
     */
    public static function placeholderRequired($transPattern)
    {
        return new self(sprintf('Translation pattern "%s" must contain %%s placeholder', $transPattern));
    }
}

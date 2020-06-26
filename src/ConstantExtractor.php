<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use ReflectionClass;
use ReflectionException;
use Yokai\EnumBundle\Exception\CannotExtractConstantsException;

/**
 * @internal
 *
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConstantExtractor
{
    public static function extract(string $pattern): array
    {
        [$class, $patternRegex] = self::explode($pattern);

        return self::filter(
            self::publicConstants($class),
            $patternRegex,
            $pattern
        );
    }

    private static function filter(array $constants, string $regexp, string $pattern): array
    {
        $matchingNames = preg_grep($regexp, array_keys($constants));

        if (count($matchingNames) === 0) {
            throw CannotExtractConstantsException::noConstantMatchingPattern($pattern);
        }

        return array_values(array_intersect_key($constants, array_flip($matchingNames)));
    }

    private static function publicConstants(string $class): array
    {
        try {
            $constants = (new ReflectionClass($class))->getReflectionConstants();
        } catch (ReflectionException $exception) {
            throw CannotExtractConstantsException::classDoNotExists($class);
        }

        $list = [];
        foreach ($constants as $constant) {
            if (!$constant->isPublic()) {
                continue;
            }

            $list[$constant->getName()] = $constant->getValue();
        }

        if (count($list) === 0) {
            throw CannotExtractConstantsException::classHasNoPublicConstant($class);
        }

        return $list;
    }

    private static function explode(string $pattern): array
    {
        if (substr_count($pattern, '::') !== 1) {
            throw CannotExtractConstantsException::invalidPattern($pattern);
        }

        [$class, $constantsNamePattern] = explode('::', $pattern);

        if (substr_count($constantsNamePattern, '*') === 0) {
            throw CannotExtractConstantsException::invalidPattern($pattern);
        }

        $constantsNameRegexp = sprintf(
            '#^%s$#',
            str_replace('*', '[0-9a-zA-Z_]+', $constantsNamePattern)
        );

        return [$class, $constantsNameRegexp];
    }
}

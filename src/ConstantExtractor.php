<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use ReflectionClass;
use ReflectionException;
use Yokai\EnumBundle\Exception\LogicException;

/**
 * @internal
 *
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class ConstantExtractor
{
    /**
     * @return list<mixed>
     * @throws LogicException
     */
    public static function extract(string $pattern): array
    {
        [$class, $patternRegex] = self::explode($pattern);

        return self::filter(
            self::publicConstants($class, $pattern),
            $patternRegex,
            $pattern
        );
    }

    /**
     * @param array<string, mixed> $constants
     *
     * @return list<mixed>
     */
    private static function filter(array $constants, string $regexp, string $pattern): array
    {
        $matchingNames = \preg_grep($regexp, \array_keys($constants));

        if ($matchingNames === false || \count($matchingNames) === 0) {
            throw LogicException::cannotExtractConstants($pattern, 'Pattern matches no constant.');
        }

        return \array_values(\array_intersect_key($constants, \array_flip($matchingNames)));
    }

    /**
     * @return array<string, mixed>
     */
    private static function publicConstants(string $class, string $pattern): array
    {
        try {
            $constants = (new ReflectionClass($class))->getReflectionConstants();
        } catch (ReflectionException $exception) {
            throw LogicException::cannotExtractConstants($pattern, \sprintf('Class %s does not exists.', $class));
        }

        $list = [];
        foreach ($constants as $constant) {
            if (!$constant->isPublic()) {
                continue;
            }

            $list[$constant->getName()] = $constant->getValue();
        }

        if (\count($list) === 0) {
            throw LogicException::cannotExtractConstants(
                $pattern,
                \sprintf('Class %s has no public constant.', $class)
            );
        }

        return $list;
    }

    /**
     * @return array{string, string}
     */
    private static function explode(string $pattern): array
    {
        if (\substr_count($pattern, '::') !== 1) {
            throw LogicException::cannotExtractConstants(
                $pattern,
                'Pattern must look like Fully\\Qualified\\ClassName::CONSTANT_*.'
            );
        }

        [$class, $constantsNamePattern] = \explode('::', $pattern);

        if (\substr_count($constantsNamePattern, '*') === 0) {
            throw LogicException::cannotExtractConstants(
                $pattern,
                'Pattern must look like Fully\\Qualified\\ClassName::CONSTANT_*.'
            );
        }

        $constantsNameRegexp = \sprintf(
            '#^%s$#',
            \str_replace('*', '[0-9a-zA-Z_]+', $constantsNamePattern)
        );

        return [$class, $constantsNameRegexp];
    }
}

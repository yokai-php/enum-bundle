<?php declare(strict_types=1);

namespace Yokai\EnumBundle;

use ReflectionClass;
use ReflectionException;
use Yokai\EnumBundle\Exception\CannotExtractConstantsException;

final class ConstantExtractor
{
    public function extract(string $pattern): array
    {
        [$class, $patternRegex] = $this->explode($pattern);

        return $this->filter(
            $this->publicConstants($class),
            $patternRegex,
            $pattern
        );
    }

    private function filter(array $constants, string $regexp, string $pattern): array
    {
        $matchingNames = preg_grep($regexp, array_keys($constants));

        if (count($matchingNames) === 0) {
            throw CannotExtractConstantsException::noConstantMathingPattern($pattern);
        }

        return array_values(array_intersect_key($constants, array_flip($matchingNames)));
    }

    private function publicConstants(string $class): array
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

    private function explode(string $pattern): array
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

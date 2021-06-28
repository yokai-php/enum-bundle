<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit;

use Generator;
use PHPUnit\Framework\TestCase;
use Yokai\EnumBundle\ConstantExtractor;
use Yokai\EnumBundle\Exception\LogicException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConstantExtractorTest extends TestCase
{
    /**
     * @dataProvider malformed
     */
    public function testExtractMalformedPattern(string $pattern, string $exceptionMessage): void
    {
        $this->expectException(LogicException::class);
        if (\method_exists($this, 'expectExceptionMessageMatches')) {
            $this->expectExceptionMessageMatches($exceptionMessage);
        }

        ConstantExtractor::extract($pattern);
    }

    /**
     * @dataProvider empty
     */
    public function testExtractEmpty(string $pattern, string $exceptionMessage): void
    {
        $this->expectException(LogicException::class);
        if (\method_exists($this, 'expectExceptionMessageMatches')) {
            $this->expectExceptionMessageMatches($exceptionMessage);
        }

        ConstantExtractor::extract($pattern);
    }

    /**
     * @dataProvider successful
     */
    public function testExtractSuccessful(string $pattern, array $expectedList): void
    {
        self::assertEquals($expectedList, ConstantExtractor::extract($pattern));
    }

    public function empty(): Generator
    {
        yield 'class without constant' => [
            ClassWithoutConstant::class . '::*',
            '/Class .+ has no public constant/',
        ];
        yield 'class without public constant' => [
            ClassWithNoPublicConstant::class . '::*',
            '/Class .+ has no public constant/',
        ];
        yield 'class with constant but no match' => [
            ClassWithConstant::class . '::NO_MATCH*',
            '/Pattern matches no constant/',
        ];
    }

    public function malformed(): Generator
    {
        $invalidPatternRegexp = '/Pattern must look like Fully\\\\Qualified\\\\ClassName::CONSTANT_\*/';
        yield 'no class no constant pattern' => [
            'not a pattern',
            $invalidPatternRegexp,
        ];
        yield 'class that does not exists' => [
            'SomeClassThatDoNotExists::STATUS_*',
            '/Class SomeClassThatDoNotExists does not exists\./',
        ];
        yield 'missing constant pattern and separator' => [
            ClassWithConstant::class,
            $invalidPatternRegexp,
        ];
        yield 'missing constant pattern' => [
            ClassWithConstant::class . '::',
            $invalidPatternRegexp,
        ];
        yield 'two separator' => [
            ClassWithConstant::class . '::STATUS_*::',
            $invalidPatternRegexp,
        ];
        yield 'no * in pattern' => [
            ClassWithConstant::class . '::STATUS_ONLINE',
            $invalidPatternRegexp,
        ];
    }

    public function successful(): Generator
    {
        yield 'starting with status' => [
            ClassWithConstant::class . '::STATUS_*',
            [ClassWithConstant::STATUS_ONLINE, ClassWithConstant::STATUS_DRAFT],
        ];
        yield 'ending with online' => [
            ClassWithConstant::class . '::*_ONLINE',
            [ClassWithConstant::STATUS_ONLINE],
        ];
    }
}

/**
 * phpcs:disable
 */
class ClassWithoutConstant
{
}

/**
 * phpcs:disable
 */
class ClassWithNoPublicConstant
{
    private const PROTECTED_CONST = 'protected';
    private const PRIVATE_CONST = 'private';
}

/**
 * phpcs:disable
 */
class ClassWithConstant
{
    public const STATUS_ONLINE = 'online';
    public const STATUS_DRAFT = 'draft';
    protected const STATUS_PROTECTED = 'protected';
    private const STATUS_PRIVATE = 'private';
}

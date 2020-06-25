<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use Generator;
use Yokai\EnumBundle\ConstantExtractor;
use Yokai\EnumBundle\Exception\CannotExtractConstantsException;

class ConstantExtractorTest extends TestCase
{
    public function getExtractor(): ConstantExtractor
    {
        return new ConstantExtractor();
    }

    /**
     * @dataProvider malformed
     */
    public function testExtractMalformedPattern(string $pattern, string $exceptionMessage): void
    {
        $this->expectException(CannotExtractConstantsException::class);
        $this->expectExceptionMessageMatches($exceptionMessage);

        $this->getExtractor()->extract($pattern);
    }

    /**
     * @dataProvider empty
     */
    public function testExtractEmpty(string $pattern, string $exceptionMessage): void
    {
        $this->expectException(CannotExtractConstantsException::class);
        $this->expectExceptionMessageMatches($exceptionMessage);

        $this->getExtractor()->extract($pattern);
    }

    /**
     * @dataProvider successful
     */
    public function testExtractSuccessful(string $pattern, array $expectedList): void
    {
        self::assertEquals($expectedList, $this->getExtractor()->extract($pattern));
    }

    public function empty(): Generator
    {
        yield 'class without constant' => [
            ClassWithoutConstant::class.'::*',
            '/Class .+ has no public constant/',
        ];
        yield 'class without public constant' => [
            ClassWithNoPublicConstant::class.'::*',
            '/Class .+ has no public constant/',
        ];
        yield 'class with constant but no match' => [
            ClassWithConstant::class.'::NO_MATCH*',
            '/Pattern .+ matches no constant/',
        ];
    }

    public function malformed(): Generator
    {
        $invalidPatternRegexp = '/Constant extraction pattern must look like Fully\\\\Qualified\\\\ClassName::CONSTANT_\*\..+/';
        yield 'no class no constant pattern' => [
            'not a pattern',
            $invalidPatternRegexp,
        ];
        yield 'class that do not exists' => [
            'SomeClassThatDoNotExists::STATUS_*',
            '/Class SomeClassThatDoNotExists do not exists\./',
        ];
        yield 'missing constant pattern and separator' => [
            ClassWithConstant::class,
            $invalidPatternRegexp,
        ];
        yield 'missing constant pattern' => [
            ClassWithConstant::class.'::',
            $invalidPatternRegexp,
        ];
        yield 'two separator' => [
            ClassWithConstant::class.'::STATUS_*::',
            $invalidPatternRegexp,
        ];
        yield 'no * in pattern' => [
            ClassWithConstant::class.'::STATUS_ONLINE',
            $invalidPatternRegexp,
        ];
    }

    public function successful(): Generator
    {
        yield 'starting with status' => [
            ClassWithConstant::class.'::STATUS_*',
            [ClassWithConstant::STATUS_ONLINE, ClassWithConstant::STATUS_DRAFT],
        ];
        yield 'ending with online' => [
            ClassWithConstant::class.'::*_ONLINE',
            [ClassWithConstant::STATUS_ONLINE],
        ];
    }
}

class ClassWithoutConstant
{
}

class ClassWithNoPublicConstant
{
    private const PROTECTED_CONST = 'protected';
    private const PRIVATE_CONST = 'private';
}

class ClassWithConstant
{
    public const STATUS_ONLINE = 'online';
    public const STATUS_DRAFT = 'draft';
    protected const STATUS_PROTECTED = 'protected';
    private const STATUS_PRIVATE = 'private';
}

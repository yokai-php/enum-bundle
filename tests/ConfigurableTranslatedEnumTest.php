<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\ConfigurableTranslatedEnum;
use Yokai\EnumBundle\Exception\InvalidTranslatePatternException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConfigurableTranslatedEnumTest extends TestCase
{
    public function testConstructedWithInvalidPattern(): void
    {
        $this->expectException(InvalidTranslatePatternException::class);
        $translator = $this->prophesize(TranslatorInterface::class);
        new ConfigurableTranslatedEnum($translator->reveal(), 'invalid.pattern', 'invalid', ['foo', 'bar']);
    }

    public function testTranslatedChoices(): void
    {
        /** @var ObjectProphecy|TranslatorInterface $translator */
        $translator = $this->prophesize(TranslatorInterface::class);
        $translator->trans('choice.something.foo', [], 'messages', null)->shouldBeCalled()->willReturn('FOO translated');
        $translator->trans('choice.something.bar', [], 'messages', null)->shouldBeCalled()->willReturn('BAR translated');
        $type = new ConfigurableTranslatedEnum($translator->reveal(), 'choice.something.%s', 'something', ['foo', 'bar']);

        $expectedChoices = [
            'foo' => 'FOO translated',
            'bar' => 'BAR translated',
        ];
        $this->assertEquals($expectedChoices, $type->getChoices());
    }

    public function testTranslatedWithDomainChoices(): void
    {
        /** @var ObjectProphecy|TranslatorInterface $translator */
        $translator = $this->prophesize(TranslatorInterface::class);
        $translator->trans('choice.something.foo', [], 'messages', null)->shouldNotBeCalled();
        $translator->trans('choice.something.bar', [], 'messages', null)->shouldNotBeCalled();
        $translator->trans('something.foo', [], 'choices', null)->shouldBeCalled()->willReturn('FOO translated');
        $translator->trans('something.bar', [], 'choices', null)->shouldBeCalled()->willReturn('BAR translated');
        $type = new ConfigurableTranslatedEnum($translator->reveal(), 'something.%s', 'something', ['foo', 'bar']);
        $type->setTransDomain('choices');

        $expectedChoices = [
            'foo' => 'FOO translated',
            'bar' => 'BAR translated',
        ];
        $this->assertEquals($expectedChoices, $type->getChoices());
    }
}

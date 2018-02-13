<?php

namespace Tests\Enum;

use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Translation\TranslatorInterface;
use Yokai\EnumBundle\Enum\ConfigurableTranslatedEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConfigurableTranslatedEnumTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructedWithInvalidPattern()
    {
        $this->expectException('Yokai\EnumBundle\Exception\InvalidTranslatePatternException');
        $translator = $this->prophesize('Symfony\Component\Translation\TranslatorInterface');
        new ConfigurableTranslatedEnum($translator->reveal(), 'invalid.pattern', 'invalid', ['foo', 'bar']);
    }

    public function testTranslatedChoices()
    {
        /** @var ObjectProphecy|TranslatorInterface $translator */
        $translator = $this->prophesize('Symfony\Component\Translation\TranslatorInterface');
        $translator->trans('choice.something.foo', [], 'messages', null)->shouldBeCalled()->willReturn('FOO translated');
        $translator->trans('choice.something.bar', [], 'messages', null)->shouldBeCalled()->willReturn('BAR translated');
        $type = new ConfigurableTranslatedEnum($translator->reveal(), 'choice.something.%s', 'something', ['foo', 'bar']);

        $expectedChoices = [
            'foo' => 'FOO translated',
            'bar' => 'BAR translated',
        ];
        $this->assertEquals($expectedChoices, $type->getChoices());
    }

    public function testTranslatedWithDomainChoices()
    {
        /** @var ObjectProphecy|TranslatorInterface $translator */
        $translator = $this->prophesize('Symfony\Component\Translation\TranslatorInterface');
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

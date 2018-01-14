<?php

namespace Tests\Enum;

use Yokai\EnumBundle\Tests\Fixtures\StateEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class AbstractTranslatedEnumTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructedWithInvalidPattern()
    {
        $this->expectException('Yokai\EnumBundle\Exception\InvalidTranslatePatternException');
        $translator = $this->prophesize('Symfony\Component\Translation\TranslatorInterface');
        new StateEnum($translator->reveal(), 'invalid.pattern');
    }

    public function testTranslatedChoices()
    {
        $translator = $this->prophesize('Symfony\Component\Translation\TranslatorInterface');
        $translator->trans('choice.state.new', [], 'messages', null)->shouldBeCalled()->willReturn('New');
        $translator->trans('choice.state.validated', [], 'messages', null)->shouldBeCalled()->willReturn('Validated');
        $translator->trans('choice.state.disabled', [], 'messages', null)->shouldBeCalled()->willReturn('Disabled');
        $type = new StateEnum($translator->reveal(), 'choice.state.%s');

        $expectedChoices = [
            'new'       => 'New',
            'validated' => 'Validated',
            'disabled'  => 'Disabled',
        ];
        $this->assertEquals($expectedChoices, $type->getChoices());
    }

    public function testTranslatedWithDomainChoices()
    {
        $translator = $this->prophesize('Symfony\Component\Translation\TranslatorInterface');
        $translator->trans('state.new', [], 'choices', null)->shouldBeCalled()->willReturn('New');
        $translator->trans('state.validated', [], 'choices', null)->shouldBeCalled()->willReturn('Validated');
        $translator->trans('state.disabled', [], 'choices', null)->shouldBeCalled()->willReturn('Disabled');
        $type = new StateEnum($translator->reveal(), 'state.%s');
        $type->setTransDomain('choices');

        $expectedChoices = [
            'new'       => 'New',
            'validated' => 'Validated',
            'disabled'  => 'Disabled',
        ];
        $this->assertEquals($expectedChoices, $type->getChoices());
    }
}

<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Form\Type\EnumType;
use Yokai\EnumBundle\NativeEnum;
use Yokai\EnumBundle\Tests\Unit\Fixtures\Action;
use Yokai\EnumBundle\Tests\Unit\Fixtures\ActionEnum;
use Yokai\EnumBundle\Tests\Unit\Fixtures\HTTPMethod;
use Yokai\EnumBundle\Tests\Unit\Fixtures\HTTPStatus;
use Yokai\EnumBundle\Tests\Unit\Fixtures\Picture;
use Yokai\EnumBundle\Tests\Unit\Fixtures\StateEnum;
use Yokai\EnumBundle\Tests\Unit\Form\TestExtension;
use Yokai\EnumBundle\Tests\Unit\Translator;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumTypeTest extends TypeTestCase
{
    public function testEnumOptionIsRequired(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->createForm();
    }

    public function testEnumOptionIsInvalid(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->createForm('state');
    }

    public function testEnumOptionValid(): void
    {
        $form = $this->createForm(StateEnum::class);

        self::assertEquals(
            ['New' => 'new', 'Validated' => 'validated', 'Disabled' => 'disabled'],
            $form->getConfig()->getOption('choices')
        );
    }

    /**
     * @dataProvider submit
     */
    public function testSubmit($enum, $options, $data, $expected): void
    {
        $form = $this->createForm($enum, $options);
        $form->submit($data);
        self::assertEquals($expected, $form->getData());
    }

    public static function submit(): \Generator
    {
        yield [
            StateEnum::class,
            ['enum_choice_value' => false],
            'new',
            'new',
        ];
        yield [
            StateEnum::class,
            [],
            'new',
            'new',
        ];

        yield [
            ActionEnum::class,
            ['enum_choice_value' => false],
            1,
            Action::EDIT(),
        ];
        yield [
            ActionEnum::class,
            [],
            'edit',
            Action::EDIT(),
        ];

        yield [
            Picture::class,
            ['enum_choice_value' => false],
            0,
            Picture::Landscape,
        ];
        yield [
            Picture::class,
            [],
            'Landscape',
            Picture::Landscape,
        ];

        yield [
            HTTPMethod::class,
            ['enum_choice_value' => false],
            0,
            HTTPMethod::GET,
        ];
        yield [
            HTTPMethod::class,
            [],
            'get',
            HTTPMethod::GET,
        ];

        yield [
            HTTPStatus::class,
            [],
            200,
            HTTPStatus::OK,
        ];
        yield [
            HTTPStatus::class,
            ['enum_choice_value' => false],
            0,
            HTTPStatus::OK,
        ];
    }

    protected function getExtensions(): array
    {
        $enumRegistry = new EnumRegistry();
        $enumRegistry->add(new StateEnum());
        $enumRegistry->add(new ActionEnum(new Translator([
            'action.VIEW' => 'Voir',
            'action.EDIT' => 'Modifier',
        ])));
        $enumRegistry->add(new NativeEnum(Picture::class));
        $enumRegistry->add(new NativeEnum(HTTPMethod::class));
        $enumRegistry->add(new NativeEnum(HTTPStatus::class));

        return [
            new TestExtension($enumRegistry),
        ];
    }

    private function createForm($enum = null, $options = []): FormInterface
    {
        if ($enum) {
            $options['enum'] = $enum;
        }

        return $this->factory->create(EnumType::class, null, $options);
    }
}

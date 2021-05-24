<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Form\Type\EnumType;
use Yokai\EnumBundle\Tests\Fixtures\StateEnum;
use Yokai\EnumBundle\Tests\Form\TestExtension;

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

    protected function getExtensions(): array
    {
        $enumRegistry = new EnumRegistry();
        $enumRegistry->add(new StateEnum());

        return [
            new TestExtension($enumRegistry)
        ];
    }

    private function createForm($enum = null): FormInterface
    {
        $options = [];
        if ($enum) {
            $options['enum'] = $enum;
        }

        return $this->factory->create(EnumType::class, null, $options);
    }
}

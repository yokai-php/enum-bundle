<?php declare(strict_types=1);

namespace Yokai\Enum\Tests\Bridge\Symfony\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Yokai\Enum\Bridge\Symfony\Form\Type\EnumType;
use Yokai\Enum\EnumRegistry;
use Yokai\Enum\Tests\Bridge\Symfony\Form\TestExtension;
use Yokai\Enum\Tests\Fixtures\GenderEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumTypeTest extends TypeTestCase
{
    private $enumRegistry;

    protected function setUp(): void
    {
        $this->enumRegistry = $this->prophesize(EnumRegistry::class);
        $this->enumRegistry->has('state')->willReturn(false);
        $this->enumRegistry->has(GenderEnum::class)->willReturn(true);
        $this->enumRegistry->get(GenderEnum::class)->willReturn(new GenderEnum);

        parent::setUp();
    }

    public function testEnumOptionIsRequired(): void
    {
        $this->expectException('Symfony\Component\OptionsResolver\Exception\MissingOptionsException');
        $this->createForm();
    }

    public function testEnumOptionIsInvalid(): void
    {
        $this->expectException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        $this->createForm('state');
    }

    public function testEnumOptionValid(): void
    {
        $form = $this->createForm(GenderEnum::class);

        $this->assertEquals(['Male' => 'male', 'Female' => 'female'], $form->getConfig()->getOption('choices'));
    }

    protected function getExtensions(): array
    {
        return [
            new TestExtension($this->enumRegistry->reveal())
        ];
    }

    private function createForm($enum = null): FormInterface
    {
        $options = [];
        if ($enum) {
            $options['enum'] = $enum;
        }

        if (method_exists(AbstractType::class, 'getBlockPrefix')) {
            $name = EnumType::class; //Symfony 3.x support
        } else {
            $name = 'enum'; //Symfony 2.x support
        }

        return $this->factory->create($name, null, $options);
    }
}

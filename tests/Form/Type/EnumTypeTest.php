<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Form\Type;

use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Form\Type\EnumType;
use Yokai\EnumBundle\Tests\Fixtures\GenderEnum;
use Yokai\EnumBundle\Tests\Form\TestExtension;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumTypeTest extends TypeTestCase
{
    use ProphecyTrait;

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

        return $this->factory->create(EnumType::class, null, $options);
    }
}

<?php declare(strict_types=1);

namespace Yokai\Enum\Tests\Bridge\Symfony\Form\Extension;

use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Yokai\Enum\Bridge\Symfony\Form\Type\EnumType;
use Yokai\Enum\Bridge\Symfony\Form\Extension\EnumTypeGuesser;
use Yokai\Enum\Bridge\Symfony\Validator\Constraints\Enum;
use Yokai\Enum\EnumRegistry;
use Yokai\Enum\Tests\Bridge\Symfony\Form\TestExtension;
use Yokai\Enum\Tests\Fixtures\GenderEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumTypeGuesserTest extends TypeTestCase
{
    const TEST_CLASS = 'Yokai\Enum\Tests\Bridge\Symfony\Form\Extension\EnumTypeGuesserTest_TestClass';

    const TEST_PROPERTY = 'property';

    /**
     * @var EnumTypeGuesser
     */
    private $guesser;

    /**
     * @var ObjectProphecy|EnumRegistry
     */
    private $enumRegistry;

    /**
     * @var ClassMetadata
     */
    private $metadata;

    /**
     * @var ObjectProphecy|MetadataFactoryInterface
     */
    private $metadataFactory;

    protected function setUp(): void
    {
        $this->enumRegistry = $this->prophesize(EnumRegistry::class);
        $this->enumRegistry->has('state')->willReturn(false);
        $this->enumRegistry->has(GenderEnum::class)->willReturn(true);
        $this->enumRegistry->get(GenderEnum::class)->willReturn(new GenderEnum);

        $this->metadata = new ClassMetadata(self::TEST_CLASS);
        $this->metadata->addPropertyConstraint(self::TEST_PROPERTY, new Enum(['enum' => GenderEnum::class]));
        $this->metadataFactory = $this->prophesize('Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface');
        $this->metadataFactory->getMetadataFor(self::TEST_CLASS)
            ->willReturn($this->metadata);

        $this->guesser = new EnumTypeGuesser($this->metadataFactory->reveal(), $this->enumRegistry->reveal());

        parent::setUp();
    }

    public function testGuessType(): void
    {
        $guess = new TypeGuess(
            $this->getEnumType(),
            [
                'enum' => GenderEnum::class,
                'multiple' => false,
            ],
            Guess::HIGH_CONFIDENCE
        );

        $this->assertEquals($guess, $this->guesser->guessType(self::TEST_CLASS, self::TEST_PROPERTY));
    }

    public function testGuessRequired(): void
    {
        $this->assertNull($this->guesser->guessRequired(self::TEST_CLASS, self::TEST_PROPERTY));
    }

    public function testGuessMaxLength(): void
    {
        $this->assertNull($this->guesser->guessMaxLength(self::TEST_CLASS, self::TEST_PROPERTY));
    }

    public function testGuessPattern(): void
    {
        $this->assertNull($this->guesser->guessPattern(self::TEST_CLASS, self::TEST_PROPERTY));
    }

    public function testCreateForm(): void
    {
        $class = self::TEST_CLASS;
        $form = $this->factory->create($this->getFormType(), new $class, ['data_class' => $class])
            ->add(self::TEST_PROPERTY);

        $this->assertEquals(['Male' => 'male', 'Female' => 'female'], $form->get(self::TEST_PROPERTY)->getConfig()->getOption('choices'));
    }

    protected function getFormType(): string
    {
        if (method_exists(AbstractType::class, 'getBlockPrefix')) {
            $name = FormType::class; //Symfony 3.x support
        } else {
            $name = 'form'; //Symfony 2.x support
        }

        return $name;
    }

    protected function getEnumType(): string
    {
        if (method_exists(AbstractType::class, 'getBlockPrefix')) {
            $name = EnumType::class; //Symfony 3.x support
        } else {
            $name = 'enum'; //Symfony 2.x support
        }

        return $name;
    }

    protected function getExtensions(): array
    {
        return [
            new TestExtension($this->enumRegistry->reveal(), $this->metadataFactory->reveal()),
        ];
    }
}

class EnumTypeGuesserTest_TestClass
{
    public $property;
}

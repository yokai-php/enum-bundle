<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Form\Extension;

use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Form\Extension\EnumTypeGuesser;
use Yokai\EnumBundle\Form\Type\EnumType;
use Yokai\EnumBundle\Tests\Fixtures\StateEnum;
use Yokai\EnumBundle\Tests\Form\TestExtension;
use Yokai\EnumBundle\Validator\Constraints\Enum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumTypeGuesserTest extends TypeTestCase
{
    use ProphecyTrait;

    private const TEST_CLASS = EnumTypeGuesserTest_TestClass::class;

    private const TEST_PROPERTY_DIRECT = 'direct';
    private const TEST_PROPERTY_COMPOUND = 'compound';

    /**
     * @var EnumTypeGuesser
     */
    private $guesser;

    /**
     * @var ObjectProphecy|EnumRegistry
     */
    private $enumRegistry;

    /**
     * @var ObjectProphecy|MetadataFactoryInterface
     */
    private $metadataFactory;

    protected function setUp(): void
    {
        $this->enumRegistry = new EnumRegistry();
        $this->enumRegistry->add(new StateEnum());

        $metadata = new ClassMetadata(self::TEST_CLASS);
        $metadata->addPropertyConstraint(self::TEST_PROPERTY_DIRECT, new Enum(['enum' => StateEnum::class]));
        if (class_exists(Compound::class)) {
            $metadata->addPropertyConstraint(
                self::TEST_PROPERTY_COMPOUND,
                new class extends Compound {
                    protected function getConstraints(array $options): array
                    {
                        return [new Enum(['enum' => StateEnum::class])];
                    }
                }
            );
        }
        $this->metadataFactory = $this->prophesize(MetadataFactoryInterface::class);
        $this->metadataFactory->getMetadataFor(self::TEST_CLASS)
            ->willReturn($metadata);

        $this->guesser = new EnumTypeGuesser($this->metadataFactory->reveal(), $this->enumRegistry);

        parent::setUp();
    }

    public function testGuessTypeDirect(): void
    {
        $guess = new TypeGuess(
            EnumType::class,
            [
                'enum' => StateEnum::class,
                'multiple' => false,
            ],
            Guess::HIGH_CONFIDENCE
        );

        self::assertEquals($guess, $this->guesser->guessType(self::TEST_CLASS, self::TEST_PROPERTY_DIRECT));
    }

    public function testGuessTypeCompound(): void
    {
        if (!class_exists(Compound::class)) {
            $this->markTestSkipped();
        }

        $guess = new TypeGuess(
            EnumType::class,
            [
                'enum' => StateEnum::class,
                'multiple' => false,
            ],
            Guess::HIGH_CONFIDENCE
        );

        self::assertEquals($guess, $this->guesser->guessType(self::TEST_CLASS, self::TEST_PROPERTY_COMPOUND));
    }

    public function testGuessRequired(): void
    {
        self::assertNull($this->guesser->guessRequired(self::TEST_CLASS, self::TEST_PROPERTY_DIRECT));
    }

    public function testGuessMaxLength(): void
    {
        self::assertNull($this->guesser->guessMaxLength(self::TEST_CLASS, self::TEST_PROPERTY_DIRECT));
    }

    public function testGuessPattern(): void
    {
        self::assertNull($this->guesser->guessPattern(self::TEST_CLASS, self::TEST_PROPERTY_DIRECT));
    }

    public function testCreateForm(): void
    {
        $class = self::TEST_CLASS;
        $form = $this->factory->create(FormType::class, new $class(), ['data_class' => $class])
            ->add(self::TEST_PROPERTY_DIRECT);

        self::assertEquals(
            ['New' => 'new', 'Validated' => 'validated', 'Disabled' => 'disabled'],
            $form->get(self::TEST_PROPERTY_DIRECT)->getConfig()->getOption('choices')
        );
    }

    protected function getExtensions(): array
    {
        return [
            new TestExtension($this->enumRegistry, $this->metadataFactory->reveal()),
        ];
    }
}

/**
 * phpcs:disable
 */
class EnumTypeGuesserTest_TestClass
{
    public $direct;
    public $compound;
}

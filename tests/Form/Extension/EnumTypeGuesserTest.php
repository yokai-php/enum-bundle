<?php

namespace Yokai\EnumBundle\Tests\Form\Extension;

use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Form\Extension\EnumTypeGuesser;
use Yokai\EnumBundle\Form\Type\EnumType;
use Yokai\EnumBundle\Tests\Fixtures\GenderEnum;
use Yokai\EnumBundle\Tests\Form\TestExtension;
use Yokai\EnumBundle\Validator\Constraints\Enum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumTypeGuesserTest extends TypeTestCase
{
    use ProphecyTrait;

    const TEST_CLASS = EnumTypeGuesserTest_TestClass::class;

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
        $this->metadataFactory = $this->prophesize(
            'Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface'
        );
        $this->metadataFactory->getMetadataFor(self::TEST_CLASS)
            ->willReturn($this->metadata);

        $this->guesser = new EnumTypeGuesser($this->metadataFactory->reveal(), $this->enumRegistry->reveal());

        parent::setUp();
    }

    public function testGuessType(): void
    {
        $guess = new TypeGuess(
            EnumType::class,
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
        $form = $this->factory->create(FormType::class, new $class, ['data_class' => $class])
            ->add(self::TEST_PROPERTY);

        $this->assertEquals(
            ['Male' => 'male', 'Female' => 'female'],
            $form->get(self::TEST_PROPERTY)->getConfig()->getOption('choices')
        );
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

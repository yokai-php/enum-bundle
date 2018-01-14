<?php

namespace Yokai\EnumBundle\Tests\Form\Extension;

use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Yokai\EnumBundle\Form\Extension\EnumTypeGuesser;
use Yokai\EnumBundle\Form\Type\EnumType;
use Yokai\EnumBundle\Registry\EnumRegistryInterface;
use Yokai\EnumBundle\Tests\Fixtures\GenderEnum;
use Yokai\EnumBundle\Tests\Form\TestExtension;
use Yokai\EnumBundle\Validator\Constraints\Enum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumTypeGuesserTest extends TypeTestCase
{
    const TEST_CLASS = 'Yokai\EnumBundle\Tests\Form\Extension\EnumTypeGuesserTest_TestClass';

    const TEST_PROPERTY = 'property';

    /**
     * @var EnumTypeGuesser
     */
    private $guesser;

    /**
     * @var ObjectProphecy|EnumRegistryInterface
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

    protected function setUp()
    {
        $this->enumRegistry = $this->prophesize('Yokai\EnumBundle\Registry\EnumRegistryInterface');
        $this->enumRegistry->has('state')->willReturn(false);
        $this->enumRegistry->has('gender')->willReturn(true);
        $this->enumRegistry->get('gender')->willReturn(new GenderEnum);

        $this->metadata = new ClassMetadata(self::TEST_CLASS);
        $this->metadata->addPropertyConstraint(self::TEST_PROPERTY, new Enum(['enum' => 'gender']));
        $this->metadataFactory = $this->prophesize('Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface');
        $this->metadataFactory->getMetadataFor(self::TEST_CLASS)
            ->willReturn($this->metadata);

        $this->guesser = new EnumTypeGuesser($this->metadataFactory->reveal(), $this->enumRegistry->reveal());

        parent::setUp();
    }

    public function testGuessType()
    {
        $guess = new TypeGuess(
            $this->getEnumType(),
            [
                'enum' => 'gender',
                'multiple' => false,
            ],
            Guess::HIGH_CONFIDENCE
        );

        $this->assertEquals($guess, $this->guesser->guessType(self::TEST_CLASS, self::TEST_PROPERTY));
    }

    public function testGuessRequired()
    {
        $this->assertNull($this->guesser->guessRequired(self::TEST_CLASS, self::TEST_PROPERTY));
    }

    public function testGuessMaxLength()
    {
        $this->assertNull($this->guesser->guessMaxLength(self::TEST_CLASS, self::TEST_PROPERTY));
    }

    public function testGuessPattern()
    {
        $this->assertNull($this->guesser->guessPattern(self::TEST_CLASS, self::TEST_PROPERTY));
    }

    public function testCreateForm()
    {
        $class = self::TEST_CLASS;
        $form = $this->factory->create($this->getFormType(), new $class, ['data_class' => $class])
            ->add(self::TEST_PROPERTY);

        $this->assertEquals(['Male' => 'male', 'Female' => 'female'], $form->get(self::TEST_PROPERTY)->getConfig()->getOption('choices'));
    }

    protected function getFormType()
    {
        if (method_exists(AbstractType::class, 'getBlockPrefix')) {
            $name = FormType::class; //Symfony 3.x support
        } else {
            $name = 'form'; //Symfony 2.x support
        }

        return $name;
    }

    protected function getEnumType()
    {
        if (method_exists(AbstractType::class, 'getBlockPrefix')) {
            $name = EnumType::class; //Symfony 3.x support
        } else {
            $name = 'enum'; //Symfony 2.x support
        }

        return $name;
    }

    protected function getExtensions()
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

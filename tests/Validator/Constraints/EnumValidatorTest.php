<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Tests\Fixtures\TypeEnum;
use Yokai\EnumBundle\Validator\Constraints\Enum;
use Yokai\EnumBundle\Validator\Constraints\EnumValidator;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): EnumValidator
    {
        $registry = new EnumRegistry();
        $registry->add(new TypeEnum());

        return new EnumValidator($registry);
    }

    public function testAcceptOnlyEnum(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate(null, new Choice());
    }

    public function testEnumIsRequired(): void
    {
        $this->expectException(ConstraintDefinitionException::class);
        $this->validator->validate('foo', new Enum());
    }

    public function testValidEnumIsRequired(): void
    {
        $this->expectException(ConstraintDefinitionException::class);
        $this->validator->validate('foo', new Enum('state'));
    }

    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new Enum('type'));

        $this->assertNoViolation();
    }

    public function testValidSingleEnum(): void
    {
        $this->validator->validate('customer', new Enum('type'));

        $this->assertNoViolation();
    }

    public function testInvalidSingleEnum(): void
    {
        $constraint = new Enum(['enum' => 'type', 'message' => 'myMessage']);

        $this->validator->validate('foo', $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"foo"')
            ->setCode(Choice::NO_SUCH_CHOICE_ERROR)
            ->setParameter('{{ choices }}', '"customer", "prospect"')
            ->assertRaised();
    }

    public function testValidMultipleEnum(): void
    {
        $constraint = new Enum(['enum' => 'type', 'multiple' => true]);

        $this->validator->validate(['customer', 'prospect'], $constraint);

        $this->assertNoViolation();
    }

    public function testInvalidMultipleEnum(): void
    {
        $constraint = new Enum(['enum' => 'type', 'multiple' => true, 'multipleMessage' => 'myMessage']);

        $this->validator->validate(['customer', 'foo'], $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"foo"')
            ->setInvalidValue('foo')
            ->setCode(Choice::NO_SUCH_CHOICE_ERROR)
            ->setParameter('{{ choices }}', '"customer", "prospect"')
            ->assertRaised();
    }
}

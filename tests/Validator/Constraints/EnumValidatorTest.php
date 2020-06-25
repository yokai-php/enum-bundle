<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Validator\Constraints;

use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Tests\Fixtures\GenderEnum;
use Yokai\EnumBundle\Tests\Fixtures\TypeEnum;
use Yokai\EnumBundle\Validator\Constraints\Enum;
use Yokai\EnumBundle\Validator\Constraints\EnumValidator;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumValidatorTest extends ConstraintValidatorTestCase
{
    use ProphecyTrait;

    protected function createValidator(): EnumValidator
    {
        /** @var EnumRegistry|ObjectProphecy $registry */
        $registry = $this->prophesize(EnumRegistry::class);
        $registry->has('state')->willReturn(false);
        $registry->has(GenderEnum::class)->willReturn(true);
        $registry->has('type')->willReturn(true);
        $registry->get('type')->willReturn(new TypeEnum);

        return new EnumValidator($registry->reveal());
    }

    public function testAcceptOnlyEnum(): void
    {
        $this->expectException('Symfony\Component\Validator\Exception\UnexpectedTypeException');
        $this->validator->validate(null, new Choice);
    }

    public function testEnumIsRequired(): void
    {
        $this->expectException('Symfony\Component\Validator\Exception\ConstraintDefinitionException');
        $this->validator->validate('foo', new Enum);
    }

    public function testValidEnumIsRequired(): void
    {
        $this->expectException('Symfony\Component\Validator\Exception\ConstraintDefinitionException');
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

        $violationAssertion = $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"foo"')
            ->setCode(Choice::NO_SUCH_CHOICE_ERROR);

        if (version_compare(Kernel::VERSION, '4.3', '>=')) {
            $violationAssertion->setParameter('{{ choices }}', '"customer", "prospect"');
        }

        $violationAssertion->assertRaised();
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

        $violationAssertion = $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"foo"')
            ->setInvalidValue('foo')
            ->setCode(Choice::NO_SUCH_CHOICE_ERROR);

        if (version_compare(Kernel::VERSION, '4.3', '>=')) {
            $violationAssertion->setParameter('{{ choices }}', '"customer", "prospect"');
        }

        $violationAssertion->assertRaised();
    }
}

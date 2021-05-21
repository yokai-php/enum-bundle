<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Exception\DuplicatedEnumException;
use Yokai\EnumBundle\Exception\InvalidArgumentException;
use Yokai\EnumBundle\Tests\Fixtures\GenderEnum;
use Yokai\EnumBundle\Tests\Fixtures\StateEnum;
use Yokai\EnumBundle\Tests\Fixtures\SubscriptionEnum;
use Yokai\EnumBundle\Tests\Fixtures\TypeEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumRegistryTest extends TestCase
{
    /**
     * @var EnumRegistry
     */
    private $registry;

    protected function setUp(): void
    {
        $this->registry = new EnumRegistry;
    }

    public function testAddDuplicatedException(): void
    {
        $this->expectException(DuplicatedEnumException::class);
        $this->registry->add(new GenderEnum);
        $this->registry->add(new GenderEnum);
    }

    public function testGetInvalidException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->registry->add(new GenderEnum);
        $this->registry->get('type');
    }

    public function testAddNominal(): void
    {
        /** @var TranslatorInterface|ObjectProphecy $translator */
        $translator = $this->prophesize(TranslatorInterface::class)->reveal();
        $gender = new GenderEnum;
        $state = new StateEnum($translator);
        $subscription = new SubscriptionEnum($translator);
        $type = new TypeEnum;

        $this->registry->add($gender);
        $this->registry->add($state);
        $this->registry->add($subscription);
        $this->registry->add($type);

        self::assertTrue($this->registry->has(GenderEnum::class));
        self::assertTrue($this->registry->has('state'));
        self::assertTrue($this->registry->has('subscription'));
        self::assertTrue($this->registry->has('type'));

        self::assertSame($gender, $this->registry->get(GenderEnum::class));
        self::assertSame($state, $this->registry->get('state'));
        self::assertSame($subscription, $this->registry->get('subscription'));
        self::assertSame($type, $this->registry->get('type'));
        self::assertSame(
            [GenderEnum::class => $gender, 'state' => $state, 'subscription' => $subscription, 'type' => $type],
            $this->registry->all()
        );
    }
}

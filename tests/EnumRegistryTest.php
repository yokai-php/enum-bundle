<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Exception\DuplicatedEnumException;
use Yokai\EnumBundle\Exception\InvalidEnumException;
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

    protected function tearDown(): void
    {
        unset($this->registry);
    }

    public function testAddDuplicatedException(): void
    {
        $this->expectException(DuplicatedEnumException::class);
        $this->registry->add(new GenderEnum);
        $this->registry->add(new GenderEnum);
    }

    public function testGetInvalidException(): void
    {
        $this->expectException(InvalidEnumException::class);
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

        $this->assertTrue($this->registry->has(GenderEnum::class));
        $this->assertTrue($this->registry->has('state'));
        $this->assertTrue($this->registry->has('subscription'));
        $this->assertTrue($this->registry->has('type'));

        $this->assertSame($gender, $this->registry->get(GenderEnum::class));
        $this->assertSame($state, $this->registry->get('state'));
        $this->assertSame($subscription, $this->registry->get('subscription'));
        $this->assertSame($type, $this->registry->get('type'));
        $this->assertSame(
            [GenderEnum::class => $gender, 'state' => $state, 'subscription' => $subscription, 'type' => $type],
            $this->registry->all()
        );
    }
}

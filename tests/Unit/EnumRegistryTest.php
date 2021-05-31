<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use PHPUnit\Framework\TestCase;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Exception\InvalidArgumentException;
use Yokai\EnumBundle\Exception\LogicException;
use Yokai\EnumBundle\Tests\Fixtures\StateEnum;
use Yokai\EnumBundle\Tests\Fixtures\SubscriptionEnum;
use Yokai\EnumBundle\Tests\Fixtures\TypeEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumRegistryTest extends TestCase
{
    public function testAddDuplicatedException(): void
    {
        $this->expectException(LogicException::class);
        $registry = new EnumRegistry();
        $registry->add(new StateEnum());
        $registry->add(new StateEnum());
    }

    public function testGetInvalidException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $registry = new EnumRegistry();
        $registry->add(new StateEnum());
        $registry->get('type');
    }

    public function testAddNominal(): void
    {
        $translator = new Translator([]);
        $state = new StateEnum();
        $subscription = new SubscriptionEnum($translator);
        $type = new TypeEnum();

        $registry = new EnumRegistry();
        $registry->add($state);
        $registry->add($subscription);
        $registry->add($type);

        self::assertTrue($registry->has(StateEnum::class));
        self::assertTrue($registry->has('subscription'));
        self::assertTrue($registry->has('type'));

        self::assertSame($state, $registry->get(StateEnum::class));
        self::assertSame($subscription, $registry->get('subscription'));
        self::assertSame($type, $registry->get('type'));
        self::assertSame(
            [StateEnum::class => $state, 'subscription' => $subscription, 'type' => $type],
            $registry->all()
        );
    }
}

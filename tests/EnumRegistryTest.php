<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests;

use PHPUnit\Framework\TestCase;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Exception\InvalidArgumentException;
use Yokai\EnumBundle\Exception\LogicException;
use Yokai\EnumBundle\Tests\Fixtures\GenderEnum;
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
        $registry->add(new GenderEnum());
        $registry->add(new GenderEnum());
    }

    public function testGetInvalidException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $registry = new EnumRegistry();
        $registry->add(new GenderEnum());
        $registry->get('type');
    }

    public function testAddNominal(): void
    {
        $translator = new Translator([]);
        $gender = new GenderEnum();
        $state = new StateEnum($translator);
        $subscription = new SubscriptionEnum($translator);
        $type = new TypeEnum();

        $registry = new EnumRegistry();
        $registry->add($gender);
        $registry->add($state);
        $registry->add($subscription);
        $registry->add($type);

        self::assertTrue($registry->has(GenderEnum::class));
        self::assertTrue($registry->has('state'));
        self::assertTrue($registry->has('subscription'));
        self::assertTrue($registry->has('type'));

        self::assertSame($gender, $registry->get(GenderEnum::class));
        self::assertSame($state, $registry->get('state'));
        self::assertSame($subscription, $registry->get('subscription'));
        self::assertSame($type, $registry->get('type'));
        self::assertSame(
            [GenderEnum::class => $gender, 'state' => $state, 'subscription' => $subscription, 'type' => $type],
            $registry->all()
        );
    }
}

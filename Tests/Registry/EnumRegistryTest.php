<?php

namespace Yokai\EnumBundle\Tests\Registry;

use Yokai\EnumBundle\Registry\EnumRegistry;
use Yokai\EnumBundle\Tests\Fixtures\GenderEnum;
use Yokai\EnumBundle\Tests\Fixtures\StateEnum;
use Yokai\EnumBundle\Tests\Fixtures\SubscriptionEnum;
use Yokai\EnumBundle\Tests\Fixtures\TypeEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumRegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EnumRegistry
     */
    private $registry;

    protected function setUp()
    {
        $this->registry = new EnumRegistry;
    }

    protected function tearDown()
    {
        unset($this->registry);
    }

    public function testAddDuplicatedException()
    {
        $this->expectException('Yokai\EnumBundle\Exception\DuplicatedEnumException');
        $this->registry->add(new GenderEnum);
        $this->registry->add(new GenderEnum);
    }

    public function testGetInvalidException()
    {
        $this->expectException('Yokai\EnumBundle\Exception\InvalidEnumException');
        $this->registry->add(new GenderEnum);
        $this->registry->get('type');
    }

    public function testAddNominal()
    {
        $translator = $this->prophesize('Symfony\Component\Translation\TranslatorInterface')->reveal();
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

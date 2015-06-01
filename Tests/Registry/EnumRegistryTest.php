<?php

namespace Octo\EnumBundle\Tests\Registry;

use Octo\EnumBundle\Registry\EnumRegistry;
use Octo\EnumBundle\Tests\GenderEnum;
use Octo\EnumBundle\Tests\StateEnum;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
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
        $this->setExpectedException('Octo\EnumBundle\Exception\DuplicatedEnumException');
        $this->registry->add(new GenderEnum);
        $this->registry->add(new GenderEnum);
    }

    public function testGetInvalidException()
    {
        $this->setExpectedException('Octo\EnumBundle\Exception\InvalidEnumException');
        $this->registry->add(new GenderEnum);
        $this->registry->get('type');
    }

    public function testAddNominal()
    {
        $gender = new GenderEnum;
        $type   = new StateEnum(
            $this->prophesize('Symfony\Component\Translation\TranslatorInterface')->reveal(),
            'choice.state.%s'
        );

        $this->registry->add($gender);
        $this->registry->add($type);

        $this->assertTrue($this->registry->has('gender'));
        $this->assertTrue($this->registry->has('state'));

        $this->assertSame($gender, $this->registry->get('gender'));
        $this->assertSame($type, $this->registry->get('state'));
    }
}

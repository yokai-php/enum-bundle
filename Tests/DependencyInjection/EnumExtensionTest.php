<?php

namespace Yokai\EnumBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yokai\EnumBundle\DependencyInjection\EnumExtension;
use Yokai\EnumBundle\Enum\EnumInterface;
use Yokai\EnumBundle\Registry\EnumRegistryInterface;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return EnumExtension
     */
    public function extension()
    {
        return new EnumExtension();
    }

    /**
     * @test
     */
    public function it_register_enum_for_autoconfiguration_by_default()
    {
        $container = new ContainerBuilder();
        $this->extension()->load([[]], $container);

        if (method_exists($container, 'getAutoconfiguredInstanceof')) {
            $autoconfigure = $container->getAutoconfiguredInstanceof();
            $this->assertArrayHasKey(EnumInterface::class, $autoconfigure);
            $this->assertEquals(['enum' => [[]]], $autoconfigure[EnumInterface::class]->getTags());
        } else {
            $this->assertTrue(true); // not in this version
        }
    }

    /**
     * @test
     */
    public function it_do_not_register_enum_for_autoconfiguration_if_asked_to()
    {
        $container = new ContainerBuilder();
        $this->extension()->load([['enum_autoconfiguration' => false]], $container);

        if (method_exists($container, 'getAutoconfiguredInstanceof')) {
            $autoconfigure = $container->getAutoconfiguredInstanceof();
            $this->assertArrayNotHasKey(EnumInterface::class, $autoconfigure);
        } else {
            $this->assertTrue(true); // not in this version
        }
    }

    /**
     * @test
     */
    public function it_register_enum_registry_alias_for_autowire_by_default()
    {
        $container = new ContainerBuilder();
        $this->extension()->load([[]], $container);
        $this->assertTrue($container->hasAlias(EnumRegistryInterface::class));
    }

    /**
     * @test
     */
    public function it_do_not_register_enum_registry_alias_for_autowire_if_asked_to()
    {
        $container = new ContainerBuilder();
        $this->extension()->load([['enum_registry_autoconfigurable' => false]], $container);
        $this->assertFalse($container->hasAlias(EnumRegistryInterface::class));
    }
}

<?php declare(strict_types=1);

namespace Yokai\Enum\Tests\Bridge\Symfony\Bundle\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yokai\Enum\Bridge\Symfony\Bundle\DependencyInjection\EnumExtension;
use Yokai\Enum\EnumInterface;
use Yokai\Enum\EnumRegistry;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumExtensionTest extends TestCase
{
    public function extension(): EnumExtension
    {
        return new EnumExtension();
    }

    /**
     * @test
     */
    public function it_register_enum_for_autoconfiguration_by_default(): void
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
    public function it_do_not_register_enum_for_autoconfiguration_if_asked_to(): void
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
    public function it_register_enum_registry_alias_for_autowire_by_default(): void
    {
        $container = new ContainerBuilder();
        $this->extension()->load([[]], $container);
        $this->assertTrue($container->hasAlias(EnumRegistry::class));
    }

    /**
     * @test
     */
    public function it_do_not_register_enum_registry_alias_for_autowire_if_asked_to(): void
    {
        $container = new ContainerBuilder();
        $this->extension()->load([['enum_registry_autoconfigurable' => false]], $container);
        $this->assertFalse($container->hasAlias(EnumRegistry::class));
    }


    /**
     * @test
     */
    public function it_register_services(): void
    {
        $container = new ContainerBuilder();
        $this->extension()->load([[]], $container);
        $services = ['form_type.enum', 'form_extention.type_guesser.enum', 'validator.enum', 'twig.extension.enum'];

        foreach ($services as $service) {
            self::assertTrue($container->has($service), sprintf('Service "%s" is registered', $service));
        }
    }
}

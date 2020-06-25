<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yokai\EnumBundle\DependencyInjection\EnumExtension;
use Yokai\EnumBundle\EnumInterface;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Tests\TestCase;

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

        $autoconfigure = $container->getAutoconfiguredInstanceof();
        $this->assertArrayHasKey(EnumInterface::class, $autoconfigure);
        $this->assertEquals(['enum' => [[]]], $autoconfigure[EnumInterface::class]->getTags());
    }

    /**
     * @test
     */
    public function it_do_not_register_enum_for_autoconfiguration_if_asked_to(): void
    {
        $container = new ContainerBuilder();
        $this->extension()->load([['enum_autoconfiguration' => false]], $container);

        $autoconfigure = $container->getAutoconfiguredInstanceof();
        $this->assertArrayNotHasKey(EnumInterface::class, $autoconfigure);
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
        $services = [
            'yokai_enum.form_type.enum_type',
            'yokai_enum.form_extension.enum_type_guesser',
            'yokai_enum.validator_constraints.enum_validator',
            'yokai_enum.twig_extension.enum_extension',
        ];

        foreach ($services as $service) {
            self::assertTrue($container->has($service), sprintf('Service "%s" is registered', $service));
        }
    }
}

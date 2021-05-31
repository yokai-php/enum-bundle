<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yokai\EnumBundle\DependencyInjection\EnumExtension;
use Yokai\EnumBundle\EnumInterface;
use Yokai\EnumBundle\EnumRegistry;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumExtensionTest extends TestCase
{
    public function testLoad(): void
    {
        $container = new ContainerBuilder();
        (new EnumExtension())->load([[]], $container);

        $services = [
            'yokai_enum.form_type.enum_type',
            'yokai_enum.form_extension.enum_type_guesser',
            'yokai_enum.validator_constraints.enum_validator',
            'yokai_enum.twig_extension.enum_extension',
        ];
        foreach ($services as $service) {
            self::assertTrue($container->has($service), sprintf('Service "%s" is registered', $service));
        }

        $autoconfigure = $container->getAutoconfiguredInstanceof();
        self::assertArrayHasKey(EnumInterface::class, $autoconfigure);
        self::assertEquals(['yokai_enum.enum' => [[]]], $autoconfigure[EnumInterface::class]->getTags());

        self::assertTrue($container->hasAlias(EnumRegistry::class));
    }
}

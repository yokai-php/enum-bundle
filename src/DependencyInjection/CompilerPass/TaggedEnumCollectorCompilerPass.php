<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class TaggedEnumCollectorCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('yokai_enum.enum_registry')) {
            return;
        }

        $registry = $container->getDefinition('yokai_enum.enum_registry');

        foreach (array_keys($container->findTaggedServiceIds('yokai_enum.enum')) as $enum) {
            $registry->addMethodCall('add', [new Reference($enum)]);
        }
    }
}

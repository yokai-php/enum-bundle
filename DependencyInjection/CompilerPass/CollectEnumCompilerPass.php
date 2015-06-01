<?php

namespace Octo\EnumBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class CollectEnumCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('enum.registry')) {
            return;
        }

        $registry = $container->getDefinition('enum.registry');

        foreach (array_keys($container->findTaggedServiceIds('enum')) as $enum) {
            $registry->addMethodCall('add', [new Reference($enum)]);
        }
    }
}

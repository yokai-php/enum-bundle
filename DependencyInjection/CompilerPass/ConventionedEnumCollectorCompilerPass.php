<?php

namespace Yokai\EnumBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class ConventionedEnumCollectorCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('enum.register_bundles');

        if (!$bundles) {
            return;
        }

        if (true === $bundles) {
            $bundles = $container->getParameter('kernel.bundles');
        } else {
            $bundles = (array) $bundles;
        }

        foreach ($bundles as $bundleClass) {
            $declarativePass = new DeclarativeEnumCollectorCompilerPass($bundleClass);
            $declarativePass->process($container);
        }
    }
}

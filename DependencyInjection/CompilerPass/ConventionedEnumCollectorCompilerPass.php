<?php

namespace Yokai\EnumBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 *
 * @deprecated
 */
class ConventionedEnumCollectorCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('enum.register_bundles');

        if (!$bundles) {
            return;
        }

        @trigger_error(
            '"' . __CLASS__ . '" is deprecated since v2.2. Please use Symfony\'s PSR4 Service discovery instead.',
            E_USER_DEPRECATED
        );

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

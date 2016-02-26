<?php

namespace EnumBundle;

use EnumBundle\DependencyInjection\CompilerPass\ConventionedEnumCollectorCompilerPass;
use EnumBundle\DependencyInjection\CompilerPass\TaggedEnumCollectorCompilerPass;
use EnumBundle\DependencyInjection\EnumExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class EnumBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        if ($bundles = $container->getParameter('enum.register_bundles')) {
            if (true === $bundles) {
                $bundles = $container->getParameter('kernel.bundles');
            } else {
                $bundles = (array) $bundles;
            }
            $container->addCompilerPass(new ConventionedEnumCollectorCompilerPass($bundles));
        }
        $container->addCompilerPass(new TaggedEnumCollectorCompilerPass);
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new EnumExtension;
    }
}

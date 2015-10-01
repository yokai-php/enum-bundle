<?php

namespace EnumBundle;

use EnumBundle\DependencyInjection\CompilerPass\CollectEnumCompilerPass;
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
        $container->addCompilerPass(new CollectEnumCompilerPass);
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new EnumExtension;
    }
}

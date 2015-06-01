<?php

namespace Octo\EnumBundle;

use Octo\EnumBundle\DependencyInjection\CompilerPass\CollectEnumCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class OctoEnumBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CollectEnumCompilerPass);
    }
}

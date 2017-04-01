<?php

namespace Yokai\EnumBundle;

use Yokai\EnumBundle\DependencyInjection\CompilerPass\ConventionedEnumCollectorCompilerPass;
use Yokai\EnumBundle\DependencyInjection\CompilerPass\TaggedEnumCollectorCompilerPass;
use Yokai\EnumBundle\DependencyInjection\EnumExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class YokaiEnumBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container
            ->addCompilerPass(new ConventionedEnumCollectorCompilerPass())
            ->addCompilerPass(new TaggedEnumCollectorCompilerPass)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new EnumExtension;
    }
}

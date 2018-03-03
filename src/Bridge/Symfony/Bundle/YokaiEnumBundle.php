<?php

namespace Yokai\Enum\Bridge\Symfony\Bundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Yokai\Enum\Bridge\Symfony\Bundle\DependencyInjection\CompilerPass\ConventionedEnumCollectorCompilerPass;
use Yokai\Enum\Bridge\Symfony\Bundle\DependencyInjection\CompilerPass\TaggedEnumCollectorCompilerPass;
use Yokai\Enum\Bridge\Symfony\Bundle\DependencyInjection\EnumExtension;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class YokaiEnumBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function build(ContainerBuilder $container)
    {
        $container
            ->addCompilerPass(new ConventionedEnumCollectorCompilerPass())
            ->addCompilerPass(new TaggedEnumCollectorCompilerPass)
        ;
    }

    /**
     * @inheritdoc
     */
    public function getContainerExtension()
    {
        return new EnumExtension;
    }
}

<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Yokai\EnumBundle\DependencyInjection\CompilerPass\TaggedEnumCollectorCompilerPass;
use Yokai\EnumBundle\DependencyInjection\EnumExtension;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class YokaiEnumBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function build(ContainerBuilder $container): void
    {
        $container
            ->addCompilerPass(new TaggedEnumCollectorCompilerPass())
        ;
    }

    /**
     * @inheritdoc
     */
    public function getContainerExtension(): EnumExtension
    {
        return new EnumExtension();
    }
}

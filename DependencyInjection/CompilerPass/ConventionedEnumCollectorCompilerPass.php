<?php

namespace EnumBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class ConventionedEnumCollectorCompilerPass implements CompilerPassInterface
{
    /**
     * @var array
     */
    private $bundles;

    /**
     * @param array $bundles
     */
    public function __construct(array $bundles)
    {
        $this->bundles = $bundles;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($this->bundles as $bundleClass) {
            $declarativePass = new DeclarativeEnumCollectorCompilerPass($bundleClass);
            $declarativePass->process($container);
        }
    }
}

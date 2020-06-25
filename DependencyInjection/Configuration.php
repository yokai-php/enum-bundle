<?php

namespace Yokai\EnumBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder()
    {
        if (version_compare(Kernel::VERSION, '4.2') >= 0) {
            $treeBuilder = new TreeBuilder('presta_sitemap');
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('presta_sitemap');
        }

        $rootNode
            ->children()
                ->variableNode('register_bundles')
                    ->info('[DEPRECATED] bundles for which to auto-register enums.')
                    ->defaultFalse()
                ->end()
                ->booleanNode('enum_autoconfiguration')
                    ->info('[DEPRECATED] If set to true, all services that implements EnumInterface, will obtain the "enum" tag automatically.')
                    ->defaultTrue()
                ->end()
                ->booleanNode('enum_registry_autoconfigurable')
                    ->info('[DEPRECATED] If set to true, add an alias for the enum registry so your service can ask for it via autoconfiguration.')
                    ->defaultTrue()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

<?php

namespace Yokai\EnumBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('yokai_enum');

        $rootNode
            ->children()
                ->variableNode('register_bundles')
                    ->info('[DEPRECATED] bundles for which to auto-register enums.')
                    ->defaultFalse()
                ->end()
                ->booleanNode('enum_autoconfiguration')
                    ->info('If set to true, all services that implements EnumInterface, will obtain the "enum" tag automatically.')
                    ->defaultTrue()
                ->end()
                ->booleanNode('enum_registry_autoconfigurable')
                    ->info('If set to true, add an alias for the enum registry so your service can ask for it via autoconfiguration.')
                    ->defaultTrue()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

<?php

namespace Yokai\EnumBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Yokai\EnumBundle\Enum\EnumInterface;
use Yokai\EnumBundle\Registry\EnumRegistryInterface;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if ($config['register_bundles']) {
            @trigger_error('"register_bundles" config var with value "true" is deprecated since v2.3 and will be removed in v3.0', \E_USER_DEPRECATED);
        }
        if (!$config['enum_autoconfiguration']) {
            @trigger_error('"enum_autoconfiguration" config var with value "false" is deprecated since v2.3 and will be removed in v3.0', \E_USER_DEPRECATED);
        }
        if ($config['enum_registry_autoconfigurable']) {
            @trigger_error('"enum_registry_autoconfigurable" config var with value "false" is deprecated since v2.3 and will be removed in v3.0', \E_USER_DEPRECATED);
        }

        $container->setParameter('enum.register_bundles', $config['register_bundles']);

        $xmlLoader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $xmlLoader->load('services.xml');
        $xmlLoader->load('forms.xml');
        $xmlLoader->load('validators.xml');
        $xmlLoader->load('twig.xml');

        if ($config['enum_autoconfiguration'] && method_exists($container, 'registerForAutoconfiguration')) {
            $container->registerForAutoconfiguration(EnumInterface::class)
                ->addTag('enum');
        }

        if ($config['enum_registry_autoconfigurable']) {
            $container->setAlias(EnumRegistryInterface::class, 'enum.registry');
        }
    }
}

<?php declare(strict_types=1);

namespace Yokai\Enum\Bridge\Symfony\Bundle\DependencyInjection;

use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Yokai\Enum\EnumInterface;
use Yokai\Enum\EnumRegistry;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumExtension extends Extension
{
    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $xmlLoader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $xmlLoader->load('enum.xml');

        $requiresForm = interface_exists(FormInterface::class);
        $requiresValidator = interface_exists(ValidatorInterface::class);
        $requiresTwig = class_exists(TwigBundle::class);

        if ($requiresForm) {
            $xmlLoader->load('form.xml');
            if (!$requiresValidator) {
                $container->removeDefinition('form_extention.type_guesser.enum');
            }
        }
        if ($requiresValidator) {
            $xmlLoader->load('validator.xml');
        }
        if ($requiresTwig) {
            $xmlLoader->load('twig.xml');
        }

        if ($config['enum_autoconfiguration']) {
            $container->registerForAutoconfiguration(EnumInterface::class)
                ->addTag('enum');
        }

        if ($config['enum_registry_autoconfigurable']) {
            $container->setAlias(EnumRegistry::class, 'enum.registry');
        }
    }
}

<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\DependencyInjection;

use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Yokai\EnumBundle\EnumInterface;

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

        $container->registerForAutoconfiguration(EnumInterface::class)
            ->addTag('enum');
    }
}

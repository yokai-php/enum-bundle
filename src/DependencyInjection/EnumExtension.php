<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\DependencyInjection;

use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Yokai\EnumBundle\EnumInterface;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Form\Extension\EnumTypeGuesser;
use Yokai\EnumBundle\Form\Type\EnumType;
use Yokai\EnumBundle\Twig\Extension\EnumExtension as EnumTwigExtension;
use Yokai\EnumBundle\Validator\Constraints\EnumValidator;

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
        $container->register('yokai_enum.enum_registry', EnumRegistry::class);
        $container->setAlias(EnumRegistry::class, 'yokai_enum.enum_registry');

        $registry = new Reference(EnumRegistry::class);

        $requiresForm = interface_exists(FormInterface::class);
        $requiresValidator = interface_exists(ValidatorInterface::class);
        $requiresTwig = class_exists(TwigBundle::class);

        if ($requiresForm) {
            $container->register('yokai_enum.form_type.enum_type', EnumType::class)
                ->setArgument('$enumRegistry', $registry)
                ->addTag('form.type');
            if ($requiresValidator) {
                $container->register('yokai_enum.form_extension.enum_type_guesser', EnumTypeGuesser::class)
                    ->setArgument('$metadataFactory', new Reference('validator.mapping.class_metadata_factory'))
                    ->addTag('form.type_guesser');
            }
        }
        if ($requiresValidator) {
            $container->register('yokai_enum.validator_constraints.enum_validator', EnumValidator::class)
                ->setArgument('$enumRegistry', $registry)
                ->addTag('validator.constraint_validator');
        }
        if ($requiresTwig) {
            $container->register('yokai_enum.twig_extension.enum_extension', EnumTwigExtension::class)
                ->setArgument('$enumRegistry', $registry)
                ->addTag('twig.extension');
        }

        $container->registerForAutoconfiguration(EnumInterface::class)
            ->addTag('yokai_enum.enum');
    }
}

<?php

namespace Yokai\EnumBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Yokai\EnumBundle\Registry\EnumRegistryInterface;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumType extends AbstractType
{
    /**
     * @var EnumRegistryInterface
     */
    private $enumRegistry;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param EnumRegistryInterface $enumRegistry
     * @param TranslatorInterface $translator
     */
    public function __construct(EnumRegistryInterface $enumRegistry, TranslatorInterface $translator)
    {
        $this->enumRegistry = $enumRegistry;
        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('enum')
            ->setDefault('choices_as_values', true)
            ->setAllowedValues(
                'enum',
                function ($name) {
                    return $this->enumRegistry->has($name);
                }
            )
            ->setDefault('enum_translation_parameters', [])
            ->setDefault('enum_translation_domain', 'messages')
            ->setDefault(
                'choices',
                function (Options $options) {
                    $choices = array_map(function ($choice) use ($options) {
                        return $this->translator->trans(
                            $choice,
                            $options['enum_translation_parameters'],
                            $options['enum_translation_domain']
                        );
                    }, $this->enumRegistry->get($options['enum'])->getChoices());

                    return array_flip($choices);
                }
            )
        ;
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        if (!method_exists(AbstractType::class, 'getBlockPrefix')) {
            return 'choice'; //Symfony 2.x support
        }

        return ChoiceType::class;
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return 'enum';
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}

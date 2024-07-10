<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Yokai\EnumBundle\EnumRegistry;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class EnumExtension extends AbstractExtension
{
    /**
     * @var EnumRegistry
     */
    private $registry;

    public function __construct(EnumRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('enum_values', function ($enum) {
                return $this->registry->get($enum)->getValues();
            }),
            new TwigFunction('enum_choices', function ($enum) {
                return $this->registry->get($enum)->getChoices();
            }),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('enum_label', function ($value, $enum) {
                return $this->registry->get($enum)->getLabel($value);
            }),
        ];
    }
}

<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Yokai\EnumBundle\EnumRegistry;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumExtension extends AbstractExtension
{
    /**
     * @var EnumRegistry
     */
    private $registry;

    /**
     * @param EnumRegistry $registry
     */
    public function __construct(EnumRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @inheritdoc
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('enum_label', [$this, 'getLabel']),
            new TwigFunction('enum_choices', [$this, 'getChoices']),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('enum_label', [$this, 'getLabel']),
        ];
    }

    /**
     * @param string $value
     * @param string $enum
     *
     * @return string
     */
    public function getLabel(string $value, string $enum): string
    {
        return $this->getChoices($enum)[$value] ?? $value;
    }

    /**
     * @param string $enum
     *
     * @return array
     */
    public function getChoices(string $enum): array
    {
        return $this->registry->get($enum)->getChoices();
    }
}

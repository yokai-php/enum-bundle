<?php declare(strict_types=1);

namespace Yokai\Enum\Bridge\Twig\Extension;

use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;
use Yokai\Enum\EnumRegistry;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumExtension extends Twig_Extension
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
            new Twig_SimpleFunction('enum_label', [$this, 'getLabel']),
            new Twig_SimpleFunction('enum_choices', [$this, 'getChoices']),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFilters(): array
    {
        return [
            new Twig_SimpleFilter('enum_label', [$this, 'getLabel']),
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

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'enum';
    }
}

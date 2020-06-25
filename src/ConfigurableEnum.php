<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConfigurableEnum implements EnumInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $choices;

    /**
     * @param string $name
     * @param array  $choices
     */
    public function __construct(string $name, array $choices)
    {
        $this->name = $name;
        $this->choices = $choices;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getChoices(): array
    {
        return $this->choices;
    }
}

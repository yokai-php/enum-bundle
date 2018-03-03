<?php

namespace Yokai\Enum;

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
    public function __construct($name, array $choices)
    {
        $this->name = $name;
        $this->choices = $choices;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getChoices()
    {
        return $this->choices;
    }
}

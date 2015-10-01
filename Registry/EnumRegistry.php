<?php

namespace EnumBundle\Registry;

use EnumBundle\Enum\EnumInterface;
use EnumBundle\Exception\DuplicatedEnumException;
use EnumBundle\Exception\InvalidEnumException;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class EnumRegistry implements EnumRegistryInterface
{
    /**
     * @var EnumInterface[]
     */
    private $enums;

    /**
     * {@inheritdoc}
     */
    public function add(EnumInterface $enum)
    {
        if ($this->has($enum->getName())) {
            throw new DuplicatedEnumException($enum->getName());
        }

        $this->enums[$enum->getName()] = $enum;
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new InvalidEnumException($name);
        }

        return $this->enums[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return isset($this->enums[$name]);
    }
}

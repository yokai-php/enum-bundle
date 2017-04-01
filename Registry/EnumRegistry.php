<?php

namespace Yokai\EnumBundle\Registry;

use Yokai\EnumBundle\Enum\EnumInterface;
use Yokai\EnumBundle\Exception\DuplicatedEnumException;
use Yokai\EnumBundle\Exception\InvalidEnumException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumRegistry implements EnumRegistryInterface
{
    /**
     * @var EnumInterface[]
     */
    private $enums;

    /**
     * @inheritdoc
     */
    public function add(EnumInterface $enum)
    {
        if ($this->has($enum->getName())) {
            throw DuplicatedEnumException::alreadyRegistered($enum->getName());
        }

        $this->enums[$enum->getName()] = $enum;
    }

    /**
     * @inheritdoc
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw InvalidEnumException::nonexistent($name);
        }

        return $this->enums[$name];
    }

    /**
     * @inheritdoc
     */
    public function has($name)
    {
        return isset($this->enums[$name]);
    }
}

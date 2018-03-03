<?php

namespace Yokai\Enum;

use Yokai\Enum\Exception\DuplicatedEnumException;
use Yokai\Enum\Exception\InvalidEnumException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumRegistry
{
    /**
     * @var EnumInterface[]
     */
    private $enums;

    /**
     * @param EnumInterface $enum
     *
     * @throws DuplicatedEnumException
     */
    public function add(EnumInterface $enum)
    {
        if ($this->has($enum->getName())) {
            throw DuplicatedEnumException::alreadyRegistered($enum->getName());
        }

        $this->enums[$enum->getName()] = $enum;
    }

    /**
     * @param string $name
     *
     * @return EnumInterface
     * @throws InvalidEnumException
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw InvalidEnumException::nonexistent($name);
        }

        return $this->enums[$name];
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->enums[$name]);
    }

    /**
     * @return EnumInterface[]
     */
    public function all()
    {
        return $this->enums;
    }
}

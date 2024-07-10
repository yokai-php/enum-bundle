<?php

declare(strict_types=1);

namespace Yokai\EnumBundle;

use Yokai\EnumBundle\Exception\InvalidArgumentException;
use Yokai\EnumBundle\Exception\LogicException;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class EnumRegistry
{
    /**
     * @var EnumInterface[]
     */
    private $enums = [];

    /**
     * @throws LogicException
     */
    public function add(EnumInterface $enum): void
    {
        if ($this->has($enum->getName())) {
            throw LogicException::alreadyRegistered($enum->getName());
        }

        $this->enums[$enum->getName()] = $enum;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function get(string $name): EnumInterface
    {
        if (!$this->has($name)) {
            throw InvalidArgumentException::unregisteredEnum($name);
        }

        return $this->enums[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->enums[$name]);
    }

    /**
     * @return EnumInterface[]
     */
    public function all(): array
    {
        return $this->enums;
    }
}

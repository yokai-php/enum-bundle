<?php declare(strict_types=1);

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
    public function add(EnumInterface $enum): void
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
    public function get(string $name): EnumInterface
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
    public function has($name): bool
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
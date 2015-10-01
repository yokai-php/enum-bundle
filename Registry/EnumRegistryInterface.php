<?php

namespace EnumBundle\Registry;

use EnumBundle\Enum\EnumInterface;
use EnumBundle\Exception\DuplicatedEnumException;
use EnumBundle\Exception\InvalidEnumException;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
interface EnumRegistryInterface
{
    /**
     * @param EnumInterface $enum
     *
     * @throws DuplicatedEnumException
     */
    public function add(EnumInterface $enum);

    /**
     * @param string $name
     *
     * @return EnumInterface
     * @throws InvalidEnumException
     */
    public function get($name);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name);
}

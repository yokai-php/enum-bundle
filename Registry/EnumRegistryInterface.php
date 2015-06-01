<?php

namespace Octo\EnumBundle\Registry;

use Octo\EnumBundle\Enum\EnumInterface;
use Octo\EnumBundle\Exception\DuplicatedEnumException;
use Octo\EnumBundle\Exception\InvalidEnumException;

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

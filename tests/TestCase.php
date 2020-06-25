<?php

namespace Yokai\EnumBundle\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
abstract class TestCase extends PHPUnitTestCase
{
    use ProphecyTrait;
}

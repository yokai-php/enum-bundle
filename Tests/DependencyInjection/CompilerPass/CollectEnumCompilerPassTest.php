<?php

namespace Octo\EnumBundle\Tests\DependencyInjection\CompilerPass;

use Octo\EnumBundle\DependencyInjection\CompilerPass\CollectEnumCompilerPass;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Yann Eugoné <yann.eugone@gmail.com>
 */
class CollectEnumCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CollectEnumCompilerPass
     */
    private $compiler;

    protected function setUp()
    {
        $this->compiler = new CollectEnumCompilerPass;
    }

    protected function tearDown()
    {
        unset($this->compiler);
    }

    public function testCollectWhenServiceNotAvailable()
    {
        $compiler = $this->prophesize('Symfony\Component\DependencyInjection\ContainerBuilder');
        $compiler->hasDefinition('enum.registry')->shouldBeCalled()->willReturn(false);

        $this->compiler->process($compiler->reveal());
    }

    public function testCollectEnums()
    {
        $registry = $this->prophesize('Symfony\Component\DependencyInjection\Definition');
        $registry->addMethodCall('add', [new Reference('enum.gender')])->shouldBeCalled();
        $registry->addMethodCall('add', [new Reference('enum.type')])->shouldBeCalled();

        $compiler = $this->prophesize('Symfony\Component\DependencyInjection\ContainerBuilder');
        $compiler->hasDefinition('enum.registry')->shouldBeCalled()->willReturn(true);
        $compiler->getDefinition('enum.registry')->shouldBeCalled()->willReturn($registry);
        $compiler->findTaggedServiceIds('enum')->shouldBeCalled()->willReturn([
            'enum.gender' => $this->prophesize('Symfony\Component\DependencyInjection\Definition')->reveal(),
            'enum.type' => $this->prophesize('Symfony\Component\DependencyInjection\Definition')->reveal(),
        ]);

        $this->compiler->process($compiler->reveal());
    }
}

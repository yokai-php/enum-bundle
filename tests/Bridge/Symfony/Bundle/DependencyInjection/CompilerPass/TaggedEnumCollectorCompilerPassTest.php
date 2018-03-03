<?php

namespace Yokai\Enum\Tests\Bridge\Symfony\Bundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Reference;
use Yokai\Enum\Bridge\Symfony\Bundle\DependencyInjection\CompilerPass\TaggedEnumCollectorCompilerPass;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class TaggedEnumCollectorCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TaggedEnumCollectorCompilerPass
     */
    private $compiler;

    protected function setUp()
    {
        $this->compiler = new TaggedEnumCollectorCompilerPass;
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

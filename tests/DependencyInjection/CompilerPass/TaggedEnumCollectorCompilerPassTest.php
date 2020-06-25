<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Yokai\EnumBundle\DependencyInjection\CompilerPass\TaggedEnumCollectorCompilerPass;
use Yokai\EnumBundle\Tests\TestCase;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class TaggedEnumCollectorCompilerPassTest extends TestCase
{
    /**
     * @var TaggedEnumCollectorCompilerPass
     */
    private $compiler;

    protected function setUp(): void
    {
        $this->compiler = new TaggedEnumCollectorCompilerPass;
    }

    public function testCollectWhenServiceNotAvailable(): void
    {
        $compiler = $this->prophesize(ContainerBuilder::class);
        $compiler->hasDefinition('enum.registry')->shouldBeCalled()->willReturn(false);

        $this->compiler->process($compiler->reveal());
    }

    public function testCollectEnums(): void
    {
        $registry = $this->prophesize(Definition::class);
        $registry->addMethodCall('add', [new Reference('enum.gender')])->shouldBeCalled();
        $registry->addMethodCall('add', [new Reference('enum.type')])->shouldBeCalled();

        $compiler = $this->prophesize(ContainerBuilder::class);
        $compiler->hasDefinition('enum.registry')->shouldBeCalled()->willReturn(true);
        $compiler->getDefinition('enum.registry')->shouldBeCalled()->willReturn($registry);
        $compiler->findTaggedServiceIds('enum')->shouldBeCalled()->willReturn([
            'enum.gender' => $this->prophesize(Definition::class)->reveal(),
            'enum.type' => $this->prophesize(Definition::class)->reveal(),
        ]);

        $this->compiler->process($compiler->reveal());
    }
}

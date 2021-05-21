<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\DependencyInjection\CompilerPass;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Yokai\EnumBundle\DependencyInjection\CompilerPass\TaggedEnumCollectorCompilerPass;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Tests\Fixtures\GenderEnum;
use Yokai\EnumBundle\Tests\Fixtures\TypeEnum;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class TaggedEnumCollectorCompilerPassTest extends TestCase
{
    public function testCollectWhenServiceNotAvailable(): void
    {
        $container = new ContainerBuilder();

        (new TaggedEnumCollectorCompilerPass())->process($container);

        self::assertTrue(true); // no exception thrown
    }

    public function testCollectEnums(): void
    {
        $container = new ContainerBuilder();
        $container->register('yokai_enum.enum_registry', EnumRegistry::class);
        $container->register('enum.gender', GenderEnum::class)
            ->addTag('yokai_enum.enum');
        $container->register('enum.type', TypeEnum::class)
            ->addTag('yokai_enum.enum');

        (new TaggedEnumCollectorCompilerPass())->process($container);

        $registry = $container->getDefinition('yokai_enum.enum_registry');
        self::assertEquals([
            ['add', [new Reference('enum.gender')]],
            ['add', [new Reference('enum.type')]],
        ], $registry->getMethodCalls());
    }
}

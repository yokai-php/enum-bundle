<?php

namespace Yokai\EnumBundle\Tests\DependencyInjection\CompilerPass;

spl_autoload_register(function ($class) {
    $file = dirname(dirname(__DIR__)).'/Fixtures/Bundles/'.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
});

use Prophecy\Argument;
use Yokai\EnumBundle\DependencyInjection\CompilerPass\ConventionedEnumCollectorCompilerPass;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class ConventionedEnumCollectorCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getBundles
     */
    public function testCollectThroughBundles($bundle, $prefix)
    {
        $container = $this->prophesize('Symfony\Component\DependencyInjection\ContainerBuilder');

        $namespace = (new \ReflectionClass($bundle))->getNamespaceName();

        $container->getParameter('enum.register_bundles')
              ->shouldBeCalled()
              ->willReturn([$bundle]);

        $container
            ->setDefinition(
                $prefix.'.enum.dummy',
                Argument::allOf(
                    Argument::type('Symfony\Component\DependencyInjection\Definition'),
                    Argument::which('getTags', ['enum' => [[]]]),
                    Argument::which('getClass', $namespace.'\Enum\DummyEnum')
                )
            )
            ->shouldBeCalled();
        $container
            ->setDefinition(
                $prefix.'.enum.customer_gender',
                Argument::allOf(
                    Argument::type('Symfony\Component\DependencyInjection\Definition'),
                    Argument::which('getTags', ['enum' => [[]]]),
                    Argument::which('getClass', $namespace.'\Enum\Customer\GenderEnum')
                )
            )
            ->shouldBeCalled();
        $container
            ->setDefinition(
                $prefix.'.enum.customer_state',
                Argument::allOf(
                    Argument::type('Symfony\Component\DependencyInjection\DefinitionDecorator'),
                    Argument::which('getTags', ['enum' => [[]]]),
                    Argument::which('getClass', $namespace.'\Enum\Customer\StateEnum'),
                    Argument::which(
                        'getArguments',
                        [
                            'customer.state.label_%s'
                        ]
                    ),
                    Argument::which('getParent', 'enum.abstract_translated')
                )
            )
            ->shouldBeCalled();

        $compiler = new ConventionedEnumCollectorCompilerPass();
        $compiler->process($container->reveal());
    }

    public function getBundles()
    {
        return [
            ['AppBundle\AppBundle', 'app'],
            ['Acme\AppBundle\AcmeAppBundle', 'acme_app'],
            ['Acme\Bundle\AppBundle\AcmeAppBundle', 'acme_app'],
        ];
    }
}

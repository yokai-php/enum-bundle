<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Integration\App;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
final class Kernel extends BaseKernel
{
    public function registerBundles(): iterable
    {
        yield new \Symfony\Bundle\FrameworkBundle\FrameworkBundle();
        yield new \Yokai\EnumBundle\YokaiEnumBundle();
        if (\PHP_VERSION_ID < 80000) {
            yield new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle();
        }
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/../config/packages/framework.yaml');
        $loader->load(__DIR__ . '/../config/packages/translation.yaml');
        if (\PHP_VERSION_ID < 80000) {
            $loader->load(__DIR__ . '/../config/packages/annotations.yaml');
        }

        $loader->load(__DIR__ . '/../config/services.yaml');
    }

    /**
     * @inheritDoc
     */
    protected function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new class implements CompilerPassInterface {
                public function process(ContainerBuilder $container)
                {
                    $container->findDefinition('form.factory')->setPublic(true);
                }
            }
        );
    }
}

<?php

declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Integration\App;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Yokai\EnumBundle\YokaiEnumBundle;

final class Kernel extends BaseKernel
{
    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new YokaiEnumBundle();
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load($this->getProjectDir() . '/config/packages/');
        $loader->load($this->getProjectDir() . '/config/services.yaml');
    }
}

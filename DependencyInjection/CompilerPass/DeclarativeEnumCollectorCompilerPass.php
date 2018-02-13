<?php

namespace Yokai\EnumBundle\DependencyInjection\CompilerPass;

use ReflectionClass;
use RuntimeException;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Yokai\EnumBundle\Enum\AbstractTranslatedEnum;
use Yokai\EnumBundle\Enum\EnumInterface;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 *
 * @deprecated
 */
class DeclarativeEnumCollectorCompilerPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $bundleDir;

    /**
     * @var string
     */
    private $bundleNamespace;

    /**
     * @var string
     */
    private $bundleName;

    /**
     * @var string
     */
    private $transDomain;

    /**
     * @param string      $bundle
     * @param string|null $transDomain
     */
    public function __construct($bundle, $transDomain = null)
    {
        $reflection = new ReflectionClass($bundle);
        $this->bundleDir = dirname($reflection->getFileName());
        $this->bundleNamespace = $reflection->getNamespaceName();
        $this->bundleName = $reflection->getShortName();

        $this->transDomain = $transDomain;
    }

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        @trigger_error(
            '"' . __CLASS__ . '" is deprecated since v2.2. Please use Symfony\'s PSR4 Service discovery instead.',
            E_USER_DEPRECATED
        );

        if (!class_exists('Symfony\Component\Finder\Finder')) {
            throw new RuntimeException('You need the symfony/finder component to register enums.');
        }

        $enumDir = $this->bundleDir . '/Enum';

        if (!is_dir($enumDir)) {
            return;
        }

        $finder = new Finder();
        $finder->files()->name('*Enum.php')->in($enumDir);

        foreach ($finder as $file) {
            /** @var SplFileInfo $file */
            $enumNamespace = $this->bundleNamespace . '\\Enum';
            if ($relativePath = $file->getRelativePath()) {
                $enumNamespace .= '\\' . strtr($relativePath, '/', '\\');
            }

            $enumClass = $enumNamespace . '\\' . $file->getBasename('.php');
            $enumReflection = new ReflectionClass($enumClass);

            if (!$enumReflection->isSubclassOf(EnumInterface::class) || $enumReflection->isAbstract()) {
                continue; //Not an enum or abstract enum
            }

            $definition = null;
            $requiredParameters = 0;
            if ($enumReflection->getConstructor()) {
                $requiredParameters = $enumReflection->getConstructor()->getNumberOfRequiredParameters();
            }

            if ($requiredParameters === 0) {
                $definition = new Definition($enumClass);
            } elseif ($requiredParameters === 2 && $enumReflection->isSubclassOf(AbstractTranslatedEnum::class)) {
                if (class_exists('Symfony\Component\DependencyInjection\ChildDefinition')) {
                    // ChildDefinition was introduced as Symfony 3.3
                    $definition = new ChildDefinition('enum.abstract_translated');
                } else {
                    // DefinitionDecorator was deprecated as Symfony 3.3
                    $definition = new DefinitionDecorator('enum.abstract_translated');
                }
                $definition->setClass($enumClass);
                $definition->addArgument(
                    $this->getTransPattern($enumClass)
                );

                if ($this->transDomain) {
                    $definition->addMethodCall(
                        'setTransDomain',
                        [
                            $this->transDomain
                        ]
                    );
                }
            }

            if (!$definition) {
                continue; //Could not determine how to create definition for the enum
            }

            $definition->addTag('enum');

            $container->setDefinition(
                $this->getServiceId($enumClass),
                $definition
            );
        }
    }

    /**
     * @param string $enumClass
     *
     * @return string
     */
    private function getServiceId($enumClass)
    {
        $enumNamespace = $this->bundleNamespace.'\\Enum\\';

        return sprintf('%s.enum.%s',
            Container::underscore(
                substr($this->bundleName, 0, -6)
            ),
            Container::underscore(
                str_replace(
                    '\\',
                    '',
                    str_replace(
                        $enumNamespace,
                        '',
                        substr($enumClass, 0, -4)
                    )
                )
            )
        );
    }

    /**
     * @param string $enumClass
     *
     * @return string
     */
    private function getTransPattern($enumClass)
    {
        $parts = array_filter(
            array_map(
                [$this, 'underscore'],
                explode(
                    '\\',
                    str_replace(
                        $this->bundleNamespace . '\\',
                        '',
                        $enumClass
                    )
                )
            )
        );

        $enum = array_pop($parts);

        return implode('_', $parts) . '.' . $enum . '.label_%s';
    }

    /**
     * @param string $input
     *
     * @return string
     */
    private function underscore($input)
    {
        return strtolower(
            preg_replace(
                '~(?<=\\w)([A-Z])~', '_$1',
                preg_replace(
                    '~(Enum|Bundle)~',
                    '',
                    $input
                )
            )
        );
    }
}

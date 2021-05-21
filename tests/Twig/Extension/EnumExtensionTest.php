<?php declare(strict_types=1);

namespace Yokai\EnumBundle\Tests\Twig\Extension;

use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Tests\Fixtures\GenderEnum;
use Yokai\EnumBundle\Tests\Fixtures\StateEnum;
use Yokai\EnumBundle\Tests\Fixtures\TypeEnum;
use Yokai\EnumBundle\Tests\Translator;
use Yokai\EnumBundle\Twig\Extension\EnumExtension;

/**
 * @author Yann Eugoné <eugone.yann@gmail.com>
 */
class EnumExtensionTest extends TestCase
{
    public function testEnumLabel(): void
    {
        $registry = new EnumRegistry();
        $registry->add(new TypeEnum());

        $twig = $this->createEnvironment($registry);

        self::assertSame(
            'Customer',
            $twig->createTemplate("{{ 'customer'|enum_label('type') }}")->render([])
        );
        self::assertSame(
            'Prospect',
            $twig->createTemplate("{{ 'prospect'|enum_label('type') }}")->render([])
        );
    }

    public function testEnumChoices(): void
    {
        $registry = new EnumRegistry();
        $registry->add(new StateEnum(new Translator([
            'choice.state.new' => 'Nouveau',
            'choice.state.validated' => 'Validé',
            'choice.state.disabled' => 'Désactivé',
        ])));

        $twig = $this->createEnvironment($registry);

        self::assertSame(
            'new,Nouveau|validated,Validé|disabled,Désactivé|',
            $twig->createTemplate(<<<TWIG
{% for label,value in enum_choices('state') %}{{ value }},{{ label }}|{% endfor %}
TWIG
            )->render([])
        );
    }

    public function testEnumValues(): void
    {
        $registry = new EnumRegistry();
        $registry->add(new GenderEnum());

        $twig = $this->createEnvironment($registry);

        self::assertSame(
            'male|female|',
            $twig->createTemplate(<<<TWIG
{% for value in enum_values('Yokai\\\\EnumBundle\\\\Tests\\\\Fixtures\\\\GenderEnum') %}{{ value }}|{% endfor %}
TWIG
            )->render([])
        );
    }

    protected function createEnvironment(EnumRegistry $registry): Environment
    {
        $loader = new ArrayLoader([]);
        $twig = new Environment($loader, ['debug' => true, 'cache' => false, 'autoescape' => false]);
        $twig->addExtension(new EnumExtension($registry));

        return $twig;
    }
}

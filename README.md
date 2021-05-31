# YokaiEnumBundle

[![Tests](https://img.shields.io/github/workflow/status/yokai-php/enum-bundle/Tests?style=flat-square&label=tests)](https://github.com/yokai-php/enum-bundle/actions)
[![Coverage](https://img.shields.io/codecov/c/github/yokai-php/enum-bundle?style=flat-square)](https://codecov.io/gh/yokai-php/enum-bundle)
[![Contributors](https://img.shields.io/github/contributors/yokai-php/enum-bundle?style=flat-square)](https://github.com/yokai-php/enum-bundle/graphs/contributors)
[![License](https://poser.pugx.org/yokai/enum-bundle/license)](https://packagist.org/packages/yokai/enum-bundle)

[![Latest Stable Version](https://img.shields.io/packagist/v/yokai/enum-bundle?style=flat-square)](https://packagist.org/packages/yokai/enum-bundle)
[![Latest Unstable Version](https://poser.pugx.org/yokai/enum-bundle/v/unstable)](https://packagist.org/packages/yokai/enum-bundle)
[![Total Downloads](https://poser.pugx.org/yokai/enum-bundle/downloads)](https://packagist.org/packages/yokai/enum-bundle)
[![Downloads Monthly](https://img.shields.io/packagist/dm/yokai/enum-bundle?style=flat-square)](https://packagist.org/packages/yokai/enum-bundle/stats)

This repository aims to provide simple enumeration implementation to Symfony.


## Installation

### Add the bundle as a dependency with Composer

```bash
$ composer require yokai/enum-bundle
```

### Enable the bundle in the kernel

```php
<?php
return [
    Yokai\EnumBundle\YokaiEnumBundle::class => ['all' => true],
];
```


## Getting started

Let's take an example : our application has some members 
and each member has a `status` which can be :
- `new`, labelled as "New"
- `validated`, labelled as "Validated"
- `disabled`, labelled as "Disabled"

### Creating the enum

We first need to create the class that will handle our enum :

```php
<?php

declare(strict_types=1);

namespace App\Enum;

use Yokai\EnumBundle\Enum;

class StatusEnum extends Enum
{
    public function __construct()
    {
        parent::__construct(['New' => 'new', 'Validated' => 'validated', 'Disabled' => 'disabled']);
    }
}
```

That's it, the bundle now knows your enum.

> **note** : every enum has a **name**. 
> That name is the enum identifier across your application. 
> You can use any string for that purpose, **as long it is unique**.
> **Using the enum class here** is a very common way to do.

### Configuring validation

We will now be able to configure `Member`'s model validation :

```php
<?php

declare(strict_types=1);

namespace App\Model;

use App\Enum\StatusEnum;
use Yokai\EnumBundle\Validator\Constraints\Enum;

class Member
{
    /**
     * @Enum(StatusEnum::class)
     */
    public ?string $status = null;
}
```

### Setting up the form

Now that validation is configured, the only thing we have to do is to add a field on our form :

```php
<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Model\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Because we added the @Enum constraint to Member::$status property
            // the bundle will be able to find out the appropriate form type automatically
            ->add('status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Member::class);
    }
}
```

### Rendering enum label

Display label of any enum value within a Twig template :

```twig
{{ member.status|enum_label('App\\Enum\\StatusEnum') }}
```

### Translating your enum

Now, maybe you will need to display the enum label in different locales.

We got you covered here with a dedicated base class for your translated enums :

```php
<?php

declare(strict_types=1);

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\TranslatedEnum;

class StatusEnum extends TranslatedEnum
{
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct(['new', 'validated', 'disabled'], $translator, 'status.%s');
    }
}
```

Now you can create the translation keys in your catalog :

```yaml
# translations/messages.en.yaml
status.new: New
status.validated: Validated
status.disabled: Disabled
# translations/messages.fr.yaml
status.new: Nouveau
status.validated: Validé
status.disabled: Désactivé
```

> **note :** the translation key format is generated using the `$transPattern` constructor argument, 
> which must be valid a [sprintf](https://www.php.net/manual/en/function.sprintf.php) pattern (containing one `%s`)


## More examples

See examples from [test suite](tests/Unit/Fixtures) & associated [tests](tests/Unit/EnumsFromFixturesTest.php).


## Recipes

- Creating [enums](docs/creating-enum.md)
- Creating [translated enums](docs/creating-translated-enum.md)
- Integration with [myclabs/php-enum](docs/myclabs-enum-integration.md)
- Migrating [from standard Symfony](docs/migrating-from-symfony-standard.md)
- Integration with [SonataAdminBundle](docs/sonata-admin-integration.md)


## MIT License

License can be found [here](https://github.com/yokai-php/enum-bundle/blob/master/Resources/meta/LICENSE).


## Authors

The bundle was originally created by [Yann Eugoné](https://github.com/yann-eugone).
See the list of [contributors](https://github.com/yokai-php/enum-bundle/contributors).

---

Thank's to [PrestaConcept](https://github.com/prestaconcept) for supporting this bundle.

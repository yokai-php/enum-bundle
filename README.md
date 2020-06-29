YokaiEnumBundle
==============

[![Latest Stable Version](https://poser.pugx.org/yokai/enum-bundle/v/stable)](https://packagist.org/packages/yokai/enum-bundle)
[![Latest Unstable Version](https://poser.pugx.org/yokai/enum-bundle/v/unstable)](https://packagist.org/packages/yokai/enum-bundle)
[![Total Downloads](https://poser.pugx.org/yokai/enum-bundle/downloads)](https://packagist.org/packages/yokai/enum-bundle)
[![License](https://poser.pugx.org/yokai/enum-bundle/license)](https://packagist.org/packages/yokai/enum-bundle)

This repository aims to provide simple enumeration implementation to Symfony.


Installation
------------

### Add the bundle as a dependency with Composer

``` bash
$ composer require yokai/enum-bundle
```

### Enable the bundle in the kernel

```php
<?php
// config/bundles.php

return [
    // ...
    Yokai\EnumBundle\YokaiEnumBundle::class => ['all' => true],
];
```


Usage
-----

Let's take an example : our application has some members 
and each member has a `gender` which can be "male" (`m`) or "female" (`f`).

We first need to create the classes that will handle our enums :

> **Note** this example is optimized for latest versions of Symfony, you will find more in dedicated doc file.

```php
<?php
// src/Enum/GenderEnum.php
namespace App\Enum;

use Yokai\EnumBundle\EnumInterface;
use Yokai\EnumBundle\EnumWithClassAsNameTrait;

class GenderEnum implements EnumInterface
{
    use EnumWithClassAsNameTrait;

    public function getChoices(): array
    {
        return ['m' => 'Male', 'f' => 'Female'];
    }
}
```

If you are using [PSR-4 service discovery](https://symfony.com/blog/new-in-symfony-3-3-psr-4-based-service-discovery),
then your service is already registered.

That's it, now the bundle know your enum services. You can start using it.

Adding validation to your model :

```php
<?php
// src/Model/Member.php
namespace App\Model;

use App\Enum\GenderEnum;
use Yokai\EnumBundle\Validator\Constraints\Enum;

class Member
{
    /**
     * @var string
     *
     * @Enum(GenderEnum::class)
     */
    protected $gender;
}
```

Adding enum form types :

```php
<?php
// src/Form/Type/MemberType.php
namespace App\Form\Type;

use App\Enum\GenderEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Yokai\EnumBundle\Form\Type\EnumType;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Let the bundle guess the form type for you (requires that you configured the validation)
            ->add('gender')

            // Manual form type binding
            ->add('gender', EnumType::class, ['enum' => GenderEnum::class])
        ;
    }
}
```

Displaying the label for an enum value within a template :

```twig
{{ value|enum_label('App\\Enum\\GenderEnum') }}
```


Recipes
-------

- Usage in [SonataAdminBundle](https://github.com/sonata-project/SonataAdminBundle) : see [doc](doc/sonata-admin.md)
- All the ways to declare [enums](doc/declaring-enum.md) or [translated enums](doc/declaring-translated-enum.md)


MIT License
-----------

License can be found [here](https://github.com/yokai-php/enum-bundle/blob/master/Resources/meta/LICENSE).


Authors
-------

The bundle was originally created by [Yann Eugoné](https://github.com/yann-eugone).
See the list of [contributors](https://github.com/yokai-php/enum-bundle/contributors).

---

Thank's to [Prestaconcept](https://github.com/prestaconcept) for supporting this bundle.

YokaiEnumBundle
==============

[![Latest Stable Version](https://poser.pugx.org/yokai/enum-bundle/v/stable)](https://packagist.org/packages/yokai/enum-bundle)
[![Latest Unstable Version](https://poser.pugx.org/yokai/enum-bundle/v/unstable)](https://packagist.org/packages/yokai/enum-bundle)
[![Total Downloads](https://poser.pugx.org/yokai/enum-bundle/downloads)](https://packagist.org/packages/yokai/enum-bundle)
[![License](https://poser.pugx.org/yokai/enum-bundle/license)](https://packagist.org/packages/yokai/enum-bundle)

[![Build Status](https://api.travis-ci.org/yokai-php/enum-bundle.png?branch=master)](https://travis-ci.org/yokai-php/enum-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yokai-php/enum-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yokai-php/enum-bundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/yokai-php/enum-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yokai-php/enum-bundle/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/596d2076-90ee-49d9-a8b2-e3bcbd390874/mini.png)](https://insight.sensiolabs.com/projects/596d2076-90ee-49d9-a8b2-e3bcbd390874)

This repository aims to provide simple enumeration implementation to Symfony :


Installation
------------

### Add the bundle as dependency with Composer

``` bash
$ composer require yokai/enum-bundle
```

### Enable the bundle in the kernel

``` php
<?php
// config/bundles.php

return [
        // ...
    Yokai\EnumBundle\YokaiEnumBundle::class => ['all' => true],
];
```


Usage
-----

Let's take an example : our application has some members and each member has a `gender` which can be "male" (`m`) or "female" (`f`).

We first need to create the classes that will handle our enums :

> **Note** this example is optimized for latest versions of Symfony, you will find more in dedicated doc file.

``` php
<?php
// src/App/Enum/Member/GenderEnum.php
namespace App\Enum\Member;

use Yokai\EnumBundle\Enum\EnumInterface;
use Yokai\EnumBundle\Enum\EnumWithClassAsNameTrait;

class GenderEnum implements EnumInterface
{
    use EnumWithClassAsNameTrait;

    public function getChoices()
    {
        return ['m' => 'Male', 'f' => 'Female'];
    }
}
```

If you are using PSR-4 service discovery, then your service is already registered.

That's it, now the bundle know your enum services. You can start using it.

Adding validation to your model :

``` php
<?php
// src/AppBundle/Model/Member.php
namespace AppBundle\Model;

use Yokai\EnumBundle\Validator\Constraints\Enum;

class Member
{
    /**
     * @var string
     *
     * @Enum("App\Enum\Member\GenderEnum")
     */
    protected $gender;
}
```

Adding enum form types :

``` php
<?php
// src/AppBundle/Form/Type/MemberType.php
namespace AppBundle\Form\Type;

use AppBundle\Enum\GenderEnum;
use AppBundle\Enum\StateEnum;
use Symfony\Component\Form\AbstractType;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Let the bundle guess the form type for you (requires that you configured the validation)
            ->add('gender')
        ;
    }
}
```

Displaying the label for an enum value within a template :

```twig
    {{ value|enum_label(constant('AppBundle\\Enum\\Member\\GenderEnum')) }}
```


Recipes
------------

- Usage in [SonataAdminBundle](https://github.com/sonata-project/SonataAdminBundle) : see [doc](Resources/doc/sonata-admin.md)
- All the ways to [declare enums](Resources/doc/declaring-enum.md)


MIT License
-----------

License can be found [here](https://github.com/yokai-php/enum-bundle/blob/master/Resources/meta/LICENSE).


Authors
-------

The bundle was originally created by [Yann Eugoné](https://github.com/yann-eugone).
See the list of [contributors](https://github.com/yokai-php/enum-bundle/contributors).

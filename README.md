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
$ php composer.phar require yokai/enum-bundle
```

### Enable the bundle in the kernel

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Yokai\EnumBundle\YokaiEnumBundle(),
    ];
}
```


Usage
-----

Let's take an example : our application has some members and each member has a `gender` and a `state`.

We first need to create the classes that will handle our enums :

``` php
<?php
// src/AppBundle/Enum/Member/GenderEnum.php
namespace AppBundle\Enum\Member;

use Yokai\EnumBundle\Enum\EnumInterface;

class GenderEnum implements EnumInterface
{
    const NAME = 'member.gender';

    public function getChoices()
    {
        return ['male' => 'Male', 'female' => 'Female'];
    }

    public function getName()
    {
        return static::NAME;
    }
}
```

``` php
<?php
// src/AppBundle/Enum/Member/StateEnum.php
namespace AppBundle\Enum\Member;

use Yokai\EnumBundle\Enum\AbstractTranslatedEnum;

class StateEnum extends AbstractTranslatedEnum
{
    const NAME = 'member.state';

    protected function getValues()
    {
        return ['new', 'validated', 'disabled'];
    }

    public function getName()
    {
        return static::NAME;
    }
}
```

Then we must declare these classes as services :

``` xml
<!-- src/AppBundle/Resources/config/services.xml -->
<services>
    <!-- ... -->

    <service id="enum.member.gender" class="AppBundle\Enum\Member\GenderEnum" public="false">
        <tag name="enum"/>
    </service>

    <service id="enum.member.state" class="AppBundle\Enum\Member\StateEnum" 
             parent="enum.abstract_translated" public="false">
        <argument>choice.member.state.%s</argument>

        <tag name="enum"/>
    </service>

</services>
```

That's it, now the bundle know your enum services. You can start using it.

Adding validation to your model :

``` php
<?php
// src/AppBundle/Model/Member.php
namespace AppBundle\Model;

use Yokai\EnumBundle\Validator\Constraints\Enum;

class Member
{
    //...

    /**
     * @var string
     *
     * @Enum("member.state")
     */
    protected $state;

    /**
     * @var string
     *
     * @Enum("member.gender")
     */
    protected $gender;

    //...
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
// For Symfony >= 2.8
use Yokai\EnumBundle\Form\Type\EnumType;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Let the bundle guess the form type for you (requires that you configured the validation)
            ->add('state')
            ->add('gender')

            // Manual form type binding for Symfony >= 2.8
            ->add('state', EnumType::class, ['enum' => StateEnum::NAME])
            ->add('gender', EnumType::class, ['enum' => GenderEnum::NAME])

            // Manual form type binding for Symfony 2.7
            ->add('state', 'enum', ['enum' => StateEnum::NAME])
            ->add('gender', 'enum', ['enum' => GenderEnum::NAME])
        ;
    }
}
```


Displaying the label for an enum value within a template :

```twig
    {{ value|enum_label(constant('AppBundle\\Enum\\Member\\StateEnum::NAME')) }}
    {{ value|enum_label(constant('AppBundle\\Enum\\Member\\GenderEnum::NAME')) }}
```


Recipes
------------

- Usage in [SonataAdminBundle](https://github.com/sonata-project/SonataAdminBundle) : see [doc](Resources/doc/sonata-admin.md)


MIT License
-----------

License can be found [here](https://github.com/yokai-php/enum-bundle/blob/master/Resources/meta/LICENSE).


Authors
-------

The bundle was originally created by [Yann Eugoné](https://github.com/yann-eugone).
See the list of [contributors](https://github.com/yokai-php/enum-bundle/contributors).

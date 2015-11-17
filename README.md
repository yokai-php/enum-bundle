EnumBundle
==============

This repository aims to provide simple enumeration implementation to Symfony2 :


[![Build Status](https://api.travis-ci.org/yann-eugone/enum-bundle.png?branch=master)](https://travis-ci.org/yann-eugone/enum-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yann-eugone/enum-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yann-eugone/enum-bundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/yann-eugone/enum-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yann-eugone/enum-bundle/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/yann-eugone/enum-bundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/yann-eugone/enum-bundle/build-status/master)
[![Total Downloads](https://poser.pugx.org/yeugone/enum-bundle/downloads.png)](https://packagist.org/packages/yeugone/enum-bundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/a3246c63-abbf-4605-98ca-33295a547338/mini.png)](https://insight.sensiolabs.com/projects/a3246c63-abbf-4605-98ca-33295a547338)


Installation
------------

### Add the bundle as dependency with Composer

``` bash
$ php composer.phar require yeugone/enum-bundle
```

### Enable the bundle in the kernel

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new EnumBundle\EnumBundle(),
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

use EnumBundle\Enum\EnumInterface;

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

use EnumBundle\Enum\AbstractTranslatedEnum;

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

use EnumBundle\Validator\Constraints\Enum;

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
//...

class MemberType extends //...
{
    //...

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //...
            ->add('state', 'enum', ['enum' => StateEnum::NAME])
            ->add('gender', 'enum', ['enum' => GenderEnum::NAME])
        ;
    }

    //...
}
```


MIT License
-----------

License can be found [here](https://github.com/yann-eugone/enum-bundle/blob/master/Resources/meta/LICENSE).


Authors
-------

The bundle was originally created by [Yann Eugoné](https://github.com/yann-eugone).
See the list of [contributors](https://github.com/yann-eugone/enum-bundle/contributors).

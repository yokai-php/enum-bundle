Declaring enum
==============

An enum is nothing more than a class with 2 methods : `getName` & `getChoices`, registered as a service.

There is a lot of way to create and declare such classes.

> **Note :** If you wish to declare translation based enum, 
> please see [dedicated documentation](declaring-translated-enum.md)

Services declarations assume that your services files has the following defaults section :

```yaml
services:
    _defaults:
        public: false
        autowire: true
        autconfigure: true
```


The classic way
---------------

Create a new class, implement both `getName` & `getChoices` methods.

```php
<?php

namespace App\Enum;

use Yokai\EnumBundle\EnumInterface;

class GenderEnum implements EnumInterface
{
    public function getName(): string
    {
        return 'gender';
    }

    public function getChoices(): array
    {
        return ['m' => 'Male', 'f' => 'Female'];
    }
}
```

Define an enum service for it (optional if you are using the default Symfony services file).

```yaml
services:
    App\Enum\GenderEnum: ~
```


The class as name way
---------------------

Create a new class, use `EnumWithClassAsNameTrait` trait and implement `getChoices` methods.

```php
<?php

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

Define an enum service for it (optional if you are using the default Symfony services file).

```yaml
services:
    App\Enum\GenderEnum: ~
```


The configurable way
--------------------

No need for a class, just use the `ConfigurableEnum` class and define a new enum service.

```yaml
services:
    enum.member.gender:
        class: Yokai\EnumBundle\ConfigurableEnum
        arguments:
            $name: 'gender'
            $choices: 
                m: 'Male'
                f: 'Female'
```


The configurable way extracting constant list
--------------------

Let say that you already have a list of constant that for the gender.
No need for a class, just use the `ConstantListEnum` class and define a new enum service.

```yaml
services:
    enum.member.gender:
        class: Yokai\EnumBundle\ConstantListEnum
        arguments:
            $constantsPattern: 'App\Model\Person::GENDER_*'
            $name: 'gender'
```

Declaring enum
==============

An enum is nothing more than a class with 2 methods : `getName` & `getChoices`, registered as a service.

There is a lot of way to create and declare such classes.

> **Note :** If you wish to declare translation based enum, 
> please see [dedicated documentation](declaring-translated-enum.md)


The classic way
---------------

Create a new class, implement both `getName` & `getChoices` methods.

```php
<?php

namespace App\Enum;

use Yokai\Enum\EnumInterface;

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

Define an enum service for it.

```yaml
services:
    enum.member.gender:
        class: 'App\Enum\GenderEnum'
        public: false
        tags: ['enum']
```


The class as name way
---------------------

Create a new class, use `EnumWithClassAsNameTrait` trait and implement `getChoices` methods.

```php
<?php

namespace App\Enum;

use Yokai\Enum\EnumInterface;
use Yokai\Enum\EnumWithClassAsNameTrait;

class GenderEnum implements EnumInterface
{
    use EnumWithClassAsNameTrait;

    public function getChoices(): array
    {
        return ['m' => 'Male', 'f' => 'Female'];
    }
}
```

Define an enum service for it.

```yaml
services:
    enum.member.gender:
        class: 'App\Enum\GenderEnum'
        public: false
        tags: ['enum']
```


The configurable way
--------------------

No need for a class, just use the `ConfigurableEnum` class and define a new enum service.

```yaml
services:
    enum.member.gender:
        class: 'Yokai\Enum\ConfigurableEnum'
        public: false
        tags: ['enum']
        arguments:
            - "gender"
            - m: 'Male'
              f: 'Female'
```


The configurable way extracting constant list
--------------------

Let say that you already have a list of constant that for the gender.
No need for a class, just use the `ConstantListEnum` class and define a new enum service.

```yaml
services:
    enum.member.gender:
        class: 'Yokai\Enum\ConstantListEnum'
        public: false
        tags: ['enum']
        arguments:
            - '@enum.constant_extractor'
            - 'App\\Model\\Person::GENDER_*'
            - "gender"
```

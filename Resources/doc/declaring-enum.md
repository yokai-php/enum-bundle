Declaring enum
==============

An enum is nothing more than a class with 2 methods : `getName` & `getChoices`, registered as a service.

There is a lot of way to create and declare such classes


The classic way
-----

Create a new class, implement both `getName` & `getChoices` methods.

```php
<?php

namespace App\Enum;

use Yokai\EnumBundle\Enum\EnumInterface;

class GenderEnum implements EnumInterface
{
    public function getName()
    {
        return 'gender';
    }

    public function getChoices()
    {
        return ['m' => 'Male', 'f' => 'Female'];
    }
}
```

Define an enum service for it.

``` yaml
services:
    enum.member.gender:
        class: 'App\Enum\GenderEnum'
        public: false
        tags: ['enum']
```


The class as name way
-----

Create a new class, use `EnumWithClassAsNameTrait` trait and implement `getChoices` methods.

```php
<?php

namespace App\Enum;

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

Define an enum service for it.

``` yaml
services:
    enum.member.gender:
        class: 'App\Enum\GenderEnum'
        public: false
        tags: ['enum']
```


The configurable way
-----

No need for a class, just use the `ConfigurableEnum` class and define a new enum service.

``` yaml
services:
    enum.member.gender:
        class: 'Yokai\EnumBundle\Enum\ConfigurableEnum'
        public: false
        tags: ['enum']
        arguments:
            - "gender"
            - m: 'Male'
              f: 'Female'
```


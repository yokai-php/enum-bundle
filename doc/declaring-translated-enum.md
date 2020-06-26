Declaring translated enum
=========================

If you wish to use Symfony's Translator for your enum labels, this bundle provide base classes to ease integration.

There is a lot of way to create and declare such classes.

> **Note :** It is pretty much like [declaring classic enums](declaring-enum.md), 
> but with a dependency to `symfony/translator`.

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

Create a new class, implement both `getName` & `getValues` methods and specify the translation pattern.

```php
<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\AbstractTranslatedEnum;

class GenderEnum extends AbstractTranslatedEnum
{
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct($translator, 'enum.gender.%s');
    }

    public function getName(): string
    {
        return 'gender';
    }

    public function getValues(): array
    {
        return ['m', 'f'];
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

Create a new class, use `EnumWithClassAsNameTrait` trait, implement `getValues` methods and specify the translation pattern.

```php
<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\AbstractTranslatedEnum;
use Yokai\EnumBundle\EnumWithClassAsNameTrait;

class GenderEnum extends AbstractTranslatedEnum
{
    use EnumWithClassAsNameTrait;

    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct($translator, 'enum.gender.%s');
    }

    public function getValues(): array
    {
        return ['m', 'f'];
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

No need for a class, just use the `ConfigurableTranslatedEnum` class and define a new enum service.

```yaml
services:
    enum.member.gender:
        class: Yokai\EnumBundle\ConfigurableTranslatedEnum
        arguments:
            $transPattern: 'enum.gender.%s'
            $name: 'gender'
            $values: ['m', 'f']
```

The configurable way extracting constant list
--------------------

Let say that you already have a list of constant that for the gender.
No need for a class, just use the `ConstantListTranslatedEnum` class and define a new enum service.

```yaml
services:
    enum.member.gender:
        class: Yokai\EnumBundle\ConstantListTranslatedEnum
        arguments:
            $constantsPattern: 'App\Model\Person::GENDER_*'
            $transPattern: 'enum.gender.%s'
            $name: 'gender'
```

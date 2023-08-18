# PHP native enum integration

Let say that you already has such enum, from [PHP](https://www.php.net/manual/en/language.enumerations.php).

```php
<?php

declare(strict_types=1);

namespace App\Model;

enum MemberStatus: string
{
    case NEW = 'new';
    case VALIDATED = 'validated';
    case DISABLED = 'disabled';
}
```

> **Note**
> Here, we are using a `StringBackedEnum`, but it is not required.
> The bundle supports any form of `UnitEnum`, backed or not.
> https://www.php.net/manual/en/language.enumerations.backed.php

## Standard enum

If you want to integrate with the bundle, you just have to declare an enum for that class.

```php
<?php

declare(strict_types=1);

namespace App\Enum;

use App\Model\MemberStatus;
use Yokai\EnumBundle\NativeEnum;

class StatusEnum extends NativeEnum
{
    public function __construct()
    {
        parent::__construct(MemberStatus::class);
    }
}
```

## Translated enum

Or if you want to translate enum constant labels.

```php
<?php

declare(strict_types=1);

namespace App\Enum;

use App\Model\MemberStatus;
use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\NativeTranslatedEnum;

class StatusEnum extends NativeTranslatedEnum
{
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct(MemberStatus::class, $translator, 'status.%s');
    }
}
```

## Submitting values through a form

Because values of enum like `StatusEnum` above are objects, it is not possible to submit it via HTTP.
As described in the [documentation](https://symfony.com/doc/current/reference/forms/types/choice.html#choice-value) Symfony will use an incrementing integer as value.
Example, with `StatusEnum` above:
- `0` will be the value for `MemberStatus::NEW`
- `1` will be the value for `MemberStatus::VALIDATED`
- `2` will be the value for `MemberStatus::DISABLED`

But, if you do not like this behavior, you can configure the form to use values instead: 
```php
<?php

namespace App\Form\Type;

use App\Enum\StatusEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Yokai\EnumBundle\Form\Type\EnumType;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('status', EnumType::class, [
            'enum' => StatusEnum::class,
            'enum_choice_value' => true,
        ]);
    }
}
```

Now, still with `StatusEnum` above:
- `"new"` will be the value for `MemberStatus::NEW`
- `"validated"` will be the value for `MemberStatus::VALIDATED`
- `"disabled"` will be the value for `MemberStatus::DISABLED`

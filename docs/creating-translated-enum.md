# Creating enum

## Extending from the enum base class

The bundle provides a very handy base class that covers most use cases.

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
        parent::__construct(__CLASS__, ['new', 'validated', 'disabled'], $translator, 'status.%s');
    }
}
```

## Extracting constants from a class

If you prefer not to store your enum values in the enum,
You can use the `ConstantListTranslatedEnum` and extract these values from constants from your model.

```php
<?php

declare(strict_types=1);

namespace App\Enum;

use App\Model\Member;
use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\ConstantListTranslatedEnum;

class StatusEnum extends ConstantListTranslatedEnum
{
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct(__CLASS__, Member::class . '::STATUS_*', $translator, 'status.%s');
    }
}
```

Then, store your enum values as public constants in your model.

```php
<?php

declare(strict_types=1);

namespace App\Model;

class Member
{
    public const STATUS_NEW = 'new';
    public const STATUS_VALIDATED = 'validated';
    public const STATUS_DISABLED = 'disabled';
}
```

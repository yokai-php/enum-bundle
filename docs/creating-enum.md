# Creating enum

## Extending from the enum base class

The bundle provides a very handy base class that covers most use cases.

```php
<?php

declare(strict_types=1);

namespace App\Enum;

use Yokai\EnumBundle\Enum;

class StatusEnum extends Enum
{
    public function __construct()
    {
        parent::__construct(['New' => 'new', 'Validated' => 'validated', 'Disabled' => 'disabled']);
    }
}
```

## Extracting constants from a class

If you prefer not to store your enum values in the enum,
You can use the `ConstantListEnum` and extract these values from constants from your model.

```php
<?php

declare(strict_types=1);

namespace App\Enum;

use App\Model\Member;
use Yokai\EnumBundle\ConstantListEnum;

class StatusEnum extends ConstantListEnum
{
    public function __construct()
    {
        parent::__construct(Member::class . '::STATUS_*');
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

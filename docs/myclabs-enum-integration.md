# myclabs/php-enum integration

Let say that you already has such enum, from [myclabs/php-enum](https://github.com/myclabs/php-enum).

```php
<?php

declare(strict_types=1);

namespace App\Model;

use MyCLabs\Enum\Enum;

/**
 * @method static self NEW
 * @method static self VALIDATED
 * @method static self DISABLED
 */
class MemberStatus extends Enum
{
    private const NEW = 'new';
    private const VALIDATED = 'validated';
    private const DISABLED = 'disabled';
}
```

## Standard enum

If you want to integrate with the bundle, you just have to declare an enum for that class.

```php
<?php

declare(strict_types=1);

namespace App\Enum;

use App\Model\MemberStatus;
use Yokai\EnumBundle\MyCLabsEnum;

class StatusEnum extends MyCLabsEnum
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
use Yokai\EnumBundle\MyCLabsTranslatedEnum;

class StatusEnum extends MyCLabsTranslatedEnum
{
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct(MemberStatus::class, $translator, 'status.%s');
    }
}
```

# Migrating from Symfony standard

Our app has members, for which we can have :
- a status which must be one of : `new`, `active` or `disabled`.
- a list of subscribed mailing lists which must be any of : `newsletter` or `commercial`.

## The code we are starting with

Our model has the properties to hold these values.
These properties are validated using using Symfony's [Choice Constraint](https://symfony.com/doc/current/reference/constraints/Choice.html).

Possible values for these properties are stored in class constant.
This is a good thing to keep these values as close as possible from the model.

```php
<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Member
{
    public const STATUS_NEW = 'new';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_DISABLED = 'disabled';

    public const SUBSCRIBE_NEWSLETTER = 'newsletter';
    public const SUBSCRIBE_COMMERCIAL = 'commercial';

    #[Assert\NotNull]
    #[Assert\Choice(choices: [Member::STATUS_NEW, Member::STATUS_ACTIVE, Member::STATUS_DISABLED])]
    public string $status = self::STATUS_NEW;

    #[Assert\Choice(choices: [Member::SUBSCRIBE_NEWSLETTER, Member::SUBSCRIBE_COMMERCIAL], multiple: true)]
    public array $subscriptions = [];
}
```

In the `messages` translation catalog, we declared our labels:

```yaml
# translations/messages.en.yaml
member:
  status:
    new: New
    active: Active
    disabled: Disabled
  subscription:
    newsletter: Our weekly newsletter
    commercial: Our montly commercial information
```

For the form associated with that model, 
we used a [ChoiceType](https://symfony.com/doc/current/reference/forms/types/choice.html)

```php
<?php

namespace App\Form\Type;

use App\Model\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'status',
                ChoiceType::class,
                [
                    'required' => true,
                    'choices' => [
                        'member.status.new' => Member::STATUS_NEW,
                        'member.status.active' => Member::STATUS_ACTIVE,
                        'member.status.disabled' => Member::STATUS_DISABLED,
                    ],
                ]
            )
            ->add(
                'subscriptions',
                ChoiceType::class,
                [
                    'required' => false,
                    'multiple' => true,
                    'choices' => [
                        'member.subscription.newsletter' => Member::SUBSCRIBE_NEWSLETTER,
                        'member.subscription.commercial' => Member::SUBSCRIBE_COMMERCIAL,
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Member::class);
    }
}
```

Finally, in a Twig template, we end up with translated concatenation for our labels:

```twig
<label>Status</label>
<p>{{ ('member.status.'~member.status)|trans }}</p>
<label>Subscriptions</label>
{% for subscription in member.subscriptions %}
    <p>{{ ('member.subscription.'~subscription)|trans }}</p>
{% endfor %}
```

## Migrating to enum

After the bundle is installed, we will start the migration.

### Creating the enums

This is the first thing to do.
Enums will centralize both possible values and labels for each value.

As the values already exists in our model, 
we will use the `ConstantListTranslatedEnum` base class to extract constants values as an enum.

```php
<?php

namespace App\Enum;

use App\Model\Member;
use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\ConstantListTranslatedEnum;

class MemberStatusEnum extends ConstantListTranslatedEnum
{
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct(Member::class . '::STATUS_*', $translator, 'member.status.%s');
    }
}
```

```php
<?php

namespace App\Enum;

use App\Model\Member;
use Symfony\Contracts\Translation\TranslatorInterface;
use Yokai\EnumBundle\ConstantListTranslatedEnum;

class MemberSubscriptionEnum extends ConstantListTranslatedEnum
{
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct(Member::class . '::SUBSCRIBE_*', $translator, 'member.subscription.%s');
    }
}
```

### Migrating the model

Now that both enum exists, we will replace the `Choice` constraint of our model properties with an `Enum` constraint.

This is in that constraint that our enum name will be written.

```diff
<?php

namespace App\Model;

+use App\Enum\MemberStatusEnum;
+use App\Enum\MemberSubscriptionEnum;
use Symfony\Component\Validator\Constraints as Assert;
+use Yokai\EnumBundle\Validator\Constraints\Enum;

class Member
{
    public const STATUS_NEW = 'new';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_DISABLED = 'disabled';

    public const SUBSCRIBE_NEWSLETTER = 'newsletter';
    public const SUBSCRIBE_COMMERCIAL = 'commercial';

    #[Assert\NotNull]
-    #[Assert\Choice(choices: [Member::STATUS_NEW, Member::STATUS_ACTIVE, Member::STATUS_DISABLED])]
+    #[Enum(enum: MemberStatusEnum::class)]
    public string $status = self::STATUS_NEW;

-    #[Assert\Choice(choices: [Member::SUBSCRIBE_NEWSLETTER, Member::SUBSCRIBE_COMMERCIAL], multiple: true)]
+    #[Enum(enum: MemberSubscriptionEnum::class, multiple: true)]
    public array $subscriptions = [];
}
```

### Migrating the form

As we associated the `Enum` constraint to our model properties, our form will now become very simple.

As far as you do not override the field type, the bundle will guess the appropriate form and options for you.

```diff
<?php

namespace App\Form\Type;

use App\Model\Member;
use Symfony\Component\Form\AbstractType;
-use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
-            ->add(
-                'status',
-                ChoiceType::class,
-                [
-                    'required' => true,
-                    'choices' => [
-                        'member.status.new' => Member::STATUS_NEW,
-                        'member.status.active' => Member::STATUS_ACTIVE,
-                        'member.status.disabled' => Member::STATUS_DISABLED,
-                    ],
-                ]
-            )
+            ->add('status')
-            ->add(
-                'subscriptions',
-                ChoiceType::class,
-                [
-                    'required' => false,
-                    'multiple' => true,
-                    'choices' => [
-                        'member.subscription.newsletter' => Member::SUBSCRIBE_NEWSLETTER,
-                        'member.subscription.commercial' => Member::SUBSCRIBE_COMMERCIAL,
-                    ],
-                ]
-            )
+            ->add('subscriptions')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Member::class);
    }
}
```

### Migrating the template

Finally, the template can be switched to use the `enum_label` filter.

That label can be applied to any enum value.
There is one only thing you need to provide : the enum name you want to use.

```diff
<label>Status</label>
-<p>{{ ('member.status.'~member.status)|trans }}</p>
+<p>{{ member.status|enum_label('App\\Enum\\MemberStatusEnum') }}</p>
<label>Subscriptions</label>
{% for subscription in member.subscriptions %}
-    <p>{{ ('member.subscription.'~subscription)|trans }}</p>
+    <p>{{ subscription|enum_label('App\\Enum\\MemberSubscriptionEnum') }}</p>
{% endfor %}
```

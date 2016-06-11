SonataAdminBundle integration
=============================

If we take our example with member that has `gender` and `state`.
And let's say we want to build a SonataAdmin for this model.

## The admin class

```php
<?php

namespace AppBundle\Admin;

use AppBundle\Enum\GenderEnum;
use AppBundle\Enum\StateEnum;
use EnumBundle\Form\Type\EnumType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class MemberAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('gender', null, [
                'template' => 'admin/member/list_gender.html.twig',
            ])
            ->add('state', null, [
                'template' => 'admin/member/list_state.html.twig',
            ])
            //...
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('gender', 'doctrine_orm_choice', [
                'field_type' => EnumType::class,
                'field_options' => [
                    'enum' => GenderEnum::NAME,
                ],
            ])
            ->add('gender', 'doctrine_orm_choice', [
                'field_type' => EnumType::class,
                'field_options' => [
                    'enum' => StateEnum::NAME,
                ],
            ])
            //...
        ;
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('gender', EnumType::class, [
                'enum' => GenderEnum::NAME,
            ])
            ->add('state', EnumType::class, [
                'enum' => StateEnum::NAME,
            ])
            //...
        ;
    }

    protected function configureShowFields(ShowMapper $form)
    {
        $form
            ->add('gender', null, [
                'template' => 'admin/member/show_gender.html.twig',
            ])
            ->add('state', null, [
                'template' => 'admin/member/show_state.html.twig',
            ])
            //...
        ;
    }
}
```

## The list templates

```twig
{# admin/member/list_gender.html.twig #}
{% extends admin.getTemplate('base_list_field') %}

{% block field %}
    {{ value|enum_label(constant('AppBundle\\Enum\\Member\\GenderEnum::NAME')) }}
{% endblock %}
```

```twig
{# admin/member/list_state.html.twig #}
{% extends admin.getTemplate('base_list_field') %}

{% block field %}
    {{ value|enum_label(constant('AppBundle\\Enum\\Member\\StateEnum::NAME')) }}
{% endblock %}
```

## The show templates

```twig
{# admin/member/show_gender.html.twig #}
{% extends admin.getTemplate('base_show_field') %}

{% block field %}
    {{ value|enum_label(constant('AppBundle\\Enum\\Member\\GenderEnum::NAME')) }}
{% endblock %}
```

```twig
{# admin/member/show_state.html.twig #}
{% extends admin.getTemplate('base_show_field') %}

{% block field %}
    {{ value|enum_label(constant('AppBundle\\Enum\\Member\\StateEnum::NAME')) }}
{% endblock %}
```

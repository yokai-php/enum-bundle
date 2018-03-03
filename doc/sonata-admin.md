SonataAdminBundle integration
=============================

If we take our example with member that has `gender`.
And let's say we want to build a SonataAdmin for this model.

## The admin class

```php
<?php

namespace App\Admin;

use App\Enum\GenderEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Yokai\Enum\Bridge\Symfony\Form\Type\EnumType;

class MemberAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('gender', null, [
                'template' => 'admin/list_gender.html.twig',
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
                    'enum' => GenderEnum::class,
                ],
            ])
            //...
        ;
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            // Let the bundle guess the form type for you (requires that you configured the validation)
            ->add('gender')
            //...
        ;
    }

    protected function configureShowFields(ShowMapper $form)
    {
        $form
            ->add('gender', null, [
                'template' => 'admin/show_gender.html.twig',
            ])
            //...
        ;
    }
}
```

## The list templates

```twig
{# admin/list_gender.html.twig #}
{% extends admin.getTemplate('base_list_field') %}

{% block field %}
    {{ value|enum_label('App\\Enum\\GenderEnum') }}
{% endblock %}
```

## The show templates

```twig
{# admin/show_gender.html.twig #}
{% extends admin.getTemplate('base_show_field') %}

{% block field %}
    {{ value|enum_label('App\\Enum\\GenderEnum') }}
{% endblock %}
```

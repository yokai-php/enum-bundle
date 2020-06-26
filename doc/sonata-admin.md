SonataAdminBundle integration
=============================

If we take our example with member that has `gender`.
And let's say we want to build a SonataAdmin for this model.

```php
<?php

namespace App\Admin;

use App\Enum\GenderEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Templating\TemplateRegistry;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter; // for ORM entities only
use Yokai\EnumBundle\EnumRegistry;
use Yokai\EnumBundle\Form\Type\EnumType;

class MemberAdmin extends AbstractAdmin
{
    /**
     * @var EnumRegistry
     */
    private $enums;

    public function __construct(EnumRegistry $enums, $code, $class, $baseControllerName = null)
    {
        $this->enums = $enums;
        parent::__construct($code, $class, $baseControllerName);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('gender', TemplateRegistry::TYPE_CHOICE, [
                'choices' => $this->enums->get(GenderEnum::class)->getChoices(),
                'catalog' => false, // enum is self translated
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('gender', ChoiceFilter::class, [
                'field_type' => EnumType::class,
                'field_options' => [
                    'enum' => GenderEnum::class,
                ],
            ])
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            // the bundle guess the form type for you, if you configured validation
            ->add('gender')
        ;
    }

    protected function configureShowFields(ShowMapper $form): void
    {
        $form
            ->add('gender', TemplateRegistry::TYPE_CHOICE, [
                'choices' => $this->enums->get(GenderEnum::class)->getChoices(),
                'catalog' => false, // enum is self translated
            ])
        ;
    }
}
```

There you go, your admin has now enum integrated :
- list choice filter : [documentation](https://sonata-project.org/bundles/doctrine-orm-admin/master/doc/reference/filter_field_definition.html)
- list datagrid column : [documentation](https://sonata-project.org/bundles/admin/3-x/doc/getting_started/the_list_view.html)
- form choice field : [documentation](https://sonata-project.org/bundles/admin/3-x/doc/getting_started/the_form_view.html)
- show field : [documentation](https://sonata-project.org/bundles/admin/3-x/doc/getting_started/the_form_view.html)

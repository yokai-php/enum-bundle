# SonataAdminBundle integration

Back to our [README.md](../README.md) example (member having `status`).
Let's say we want to build a [SonataAdmin](https://github.com/sonata-project/SonataAdminBundle) for this model.

```php
<?php

namespace App\Admin;

use App\Enum\StatusEnum;
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
            ->add('status', TemplateRegistry::TYPE_CHOICE, [
                'choices' => array_flip($this->enums->get(StatusEnum::class)->getChoices()),
                'catalog' => false, // enum is self translated
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('status', ChoiceFilter::class, [
                'field_type' => EnumType::class,
                'field_options' => ['enum' => StatusEnum::class],
            ])
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            // Because we added the #[Enum] constraint to Member::$status property
            // the bundle will be able to find out the appropriate form type automatically
            ->add('status')
        ;
    }

    protected function configureShowFields(ShowMapper $form): void
    {
        $form
            ->add('status', TemplateRegistry::TYPE_CHOICE, [
                'choices' => $this->enums->get(StatusEnum::class)->getChoices(),
                'catalog' => false, // enum is self translated
            ])
        ;
    }
}
```

There you go, your admin has now enum support for :
- [filter field](https://sonata-project.org/bundles/doctrine-orm-admin/master/doc/reference/filter_field_definition.html)
- [list field](https://sonata-project.org/bundles/admin/3-x/doc/getting_started/the_list_view.html)
- [form field](https://sonata-project.org/bundles/admin/3-x/doc/getting_started/the_form_view.html)
- [show field](https://sonata-project.org/bundles/admin/3-x/doc/reference/action_show.html)

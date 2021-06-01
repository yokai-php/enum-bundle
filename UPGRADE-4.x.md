## Changes made in `EnumInterface`

- `Yokai\EnumBundle\EnumInterface::getChoices` must now return the enum labels as keys of the array.
- `Yokai\EnumBundle\EnumInterface::getValues` was introduced 
  and must return all possible enum values.
- `Yokai\EnumBundle\EnumInterface::getLabel` was introduced 
  and must return the associated label of any provided enum value.

## Changes made in enum base classes

- `Yokai\EnumBundle\AbstractTranslatedEnum` was removed, 
  you can now extend `Yokai\EnumBundle\TranslatedEnum` and provide values as constructor argument.
- `Yokai\EnumBundle\ConfigurableTranslatedEnum` was removed, 
  you can now extend `Yokai\EnumBundle\TranslatedEnum` instead.
- `Yokai\EnumBundle\Enum` was introduced, 
  you can now extend this class and provide choices and names as constructor arguments (recommended).

## Changes made to other classes

- Non enum classes were all made final.
- `enum_label` Twig function was removed (use `enum_label` Twig filter instead).
- Most methods now throw exceptions that implements (`Yokai\EnumBundle\Exception\ExceptionInterface`).

## Changes made in documentation

- Due to how popular [service discovering](https://symfony.com/blog/new-in-symfony-3-3-psr-4-based-service-discovery) has become, 
  doc will only show how to create an enum by creating a new class. 
  But it is still 100% valid to create new enums without creating a class.

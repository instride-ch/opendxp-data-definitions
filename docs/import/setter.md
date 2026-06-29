## Setter

A setter sets data to the object during import. Setters determine how interpreted data is written to object fields.

### Built-in Setters

Data Definitions includes several built-in setters:
- **Objectbrick** - Saves data to an objectbrick
- **Localizedfield** - Saves data to specific language fields
- **Classificationstore** - Saves data to classificationstore fields
- **Fieldcollection** - Saves data to fieldcollections
- **Key** - Sets object key to a dynamic value
- **ObjectType** - Sets object type to a dynamic value
- **Relation** - Sets object relations

### Creating a Custom Setter

To create your own setter, implement the `Instride\Bundle\OpenDxpDataDefinitionsBundle\Setter\SetterInterface` interface:

```php
<?php

namespace AcmeBundle\DataDefinitions\Setter;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\Context\SetterContextInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Setter\SetterInterface;

class MyCustomSetter implements SetterInterface
{
    public function set(SetterContextInterface $context)
    {
        $object = $context->getObject();
        $value = $context->getValue();
        $mapping = $context->getMapping();
        $definition = $context->getDefinition();
        
        // Your custom setter logic here
        $toColumn = $mapping->getToColumn();
        
        // Set the value to the object
        $object->setValue($toColumn, $value);
    }
}
```

### Registering the Setter

Add your setter as a service with the `data_definitions.setter` tag:

```yaml
services:
    acme_bundle.data_definitions.my_custom_setter:
        class: AcmeBundle\DataDefinitions\Setter\MyCustomSetter
        tags:
            - { name: data_definitions.setter, type: my-custom-setter, form-type: Instride\Bundle\OpenDxpDataDefinitionsBundle\Form\Type\NoConfigurationType }
```

**Tag attributes:**
- `type`: A unique identifier for your setter (used in the UI)
- `form-type`: (Optional) The form type class for configuring your setter

### Adding Configuration Form

If your setter requires configuration, create a form type:

```php
<?php

namespace AcmeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class MyCustomSetterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('overwrite', CheckboxType::class, [
                'required' => false,
                'label' => 'Overwrite existing values',
            ])
        ;
    }
}
```

### Adding JavaScript for GUI Configuration

If your setter has complex configuration that requires a custom UI, create a JavaScript file:

```javascript
opendxp.registerNS('opendxp.plugin.datadefinitions.setters.mycustomsetter');

opendxp.plugin.datadefinitions.setters.mycustomsetter = Class.create(opendxp.plugin.datadefinitions.setters.abstract, {
    getForm: function (config) {
        // Return form configuration for your setter
        return {
            xtype: 'fieldset',
            title: t('my_custom_setter_config'),
            items: [
                {
                    xtype: 'checkbox',
                    name: 'overwrite',
                    fieldLabel: t('overwrite_existing')
                }
            ]
        };
    }
});
```

Load your JavaScript file in your configuration:

```yaml
opendxp_data_definitions:
    opendxp_admin:
        js:
            my_custom_setter: '/static/opendxp/mycustomsetter.js'
```

This will merge your custom JavaScript files with the default Data Definitions JavaScript files.

### Setter Context

The `SetterContextInterface` provides access to:

- `getObject()`: The DataObject being imported
- `getValue()`: The interpreted value to set
- `getMapping()`: The current mapping configuration
- `getDefinition()`: The import definition
- `getParams()`: Additional parameters
- `getSetterConfig()`: The setter's configuration

### Example: LocalizedfieldSetter

The built-in `LocalizedfieldSetter` demonstrates how to handle complex field types:

```php
public function set(SetterContextInterface $context)
{
    $object = $context->getObject();
    $value = $context->getValue();
    $mapping = $context->getMapping();
    
    $language = $mapping->getLanguage();
    $toColumn = $mapping->getToColumn();
    
    if ($language) {
        $object->setLocalizedfield($toColumn, $value, $language);
    } else {
        $object->setValue($toColumn, $value);
    }
}
```

### When to Use Custom Setters

Create a custom setter when you need to:
- Handle special field types not supported by built-in setters
- Implement custom data transformation before setting
- Set values to related objects or collections
- Handle complex data structures (e.g., nested objects)
- Implement conditional logic for value setting

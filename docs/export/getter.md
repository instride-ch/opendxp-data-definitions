## Getter

A getter extracts data from a DataObject during export. Getters are used to retrieve field values from objects based on the mapping configuration.

### Creating a Custom Getter

To create your own getter, implement the `Instride\Bundle\DataDefinitionsBundle\Getter\GetterInterface` interface:

```php
<?php

namespace AcmeBundle\DataDefinitions\Getter;

use Instride\Bundle\DataDefinitionsBundle\Context\GetterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Getter\GetterInterface;

class MyCustomGetter implements GetterInterface
{
    public function get(GetterContextInterface $context)
    {
        $object = $context->getObject();
        $mapping = $context->getMapping();
        $definition = $context->getDefinition();
        
        // Extract data from the object based on the mapping
        $fromColumn = $mapping->getFromColumn();
        
        // Your custom getter logic here
        $value = $object->getValue($fromColumn);
        
        return $value;
    }
}
```

### Registering the Getter

Add your getter as a service with the `data_definitions.getter` tag:

```yml
services:
    acme_bundle.data_definitions.my_custom_getter:
        class: AcmeBundle\DataDefinitions\Getter\MyCustomGetter
        tags:
            - { name: data_definitions.getter, type: my-custom-getter }
```

**Tag attributes:**
- `type`: A unique identifier for your getter (used in the UI)

### Getter Context

The `GetterContextInterface` provides access to:

- `getObject()`: The DataObject being exported
- `getMapping()`: The current mapping configuration
- `getDefinition()`: The export definition
- `getParams()`: Additional parameters

### Example: ClassificationStoreFieldGetter

The built-in `ClassificationStoreFieldGetter` demonstrates how to extract complex data:

```php
public function get(GetterContextInterface $context)
{
    $classificationStoreGetter = sprintf('get%s', ucfirst($context->getMapping()->getFromColumn()));
    
    if (method_exists($context->getObject(), $classificationStoreGetter)) {
        $classificationStore = $context->getObject()->$classificationStoreGetter();
        
        if ($classificationStore instanceof Classificationstore) {
            // Extract and format classification store data
            $groups = $classificationStore->getActiveGroups();
            $values = [];
            
            foreach ($groups as $groupId => $groupIsActive) {
                // Process each group and extract values
            }
            
            return $values;
        }
    }
    
    return null;
}
```

### Dynamic Column Getters

For getters that need to handle dynamic columns, implement the `DynamicColumnGetterInterface`:

```php
<?php

namespace AcmeBundle\DataDefinitions\Getter;

use Instride\Bundle\DataDefinitionsBundle\Context\GetterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Getter\DynamicColumnGetterInterface;
use Instride\Bundle\DataDefinitionsBundle\Getter\GetterInterface;

class DynamicGetter implements GetterInterface, DynamicColumnGetterInterface
{
    public function get(GetterContextInterface $context)
    {
        // Standard getter logic
    }
    
    public function getDynamicColumns(GetterContextInterface $context): array
    {
        // Return available dynamic columns
        return [
            'column1' => 'Column 1 Label',
            'column2' => 'Column 2 Label',
        ];
    }
}
```

### Using Getters in Export Definitions

Getters are selected in the export definition mapping for each column. The getter determines how the data is extracted from the object before being passed to the provider.

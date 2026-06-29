## Cleaner

A cleaner handles the clean-up process after import. It processes objects that were not present in the import data, typically by deleting or unpublishing them.

### Built-in Cleaners

Data Definitions includes several built-in cleaners:
- **Deleter** - Deletes missing objects
- **Unpublisher** - Unpublishes missing objects
- **Reference Cleaner** - Deletes only when no references exist, otherwise unpublishes
- **None** - Does nothing (no clean-up)

### Creating a Custom Cleaner

To create your own cleaner, implement the `Instride\Bundle\OpenDxpDataDefinitionsBundle\Cleaner\CleanerInterface` interface:

```php
<?php

namespace AcmeBundle\DataDefinitions\Cleaner;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\Cleaner\CleanerInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\DataDefinitionInterface;

class MyCustomCleaner implements CleanerInterface
{
    public function cleanup(DataDefinitionInterface $definition, array $objectIds): void
    {
        // Process objects that were not in the import data
        // $objectIds contains IDs of objects to clean up
        
        foreach ($objectIds as $objectId) {
            $object = \OpenDxp\Model\DataObject::getById($objectId);
            
            if ($object) {
                // Your custom clean-up logic here
                $this->processObject($object, $definition);
            }
        }
    }
    
    private function processObject($object, DataDefinitionInterface $definition): void
    {
        // Implement your clean-up logic
        // For example: delete, unpublish, move to archive, etc.
    }
}
```

### Registering the Cleaner

Add your cleaner as a service with the `data_definitions.cleaner` tag:

```yaml
services:
    acme_bundle.data_definitions.my_custom_cleaner:
        class: AcmeBundle\DataDefinitions\Cleaner\MyCustomCleaner
        tags:
            - { name: data_definitions.cleaner, type: my-custom-cleaner }
```

**Tag attributes:**
- `type`: A unique identifier for your cleaner (used in the UI)

### Cleaner Parameters

The `cleanup` method receives:
- `$definition`: The import/export definition
- `$objectIds`: Array of object IDs that were not present in the import data

### Example: Deleter

The built-in `Deleter` demonstrates a simple implementation:

```php
public function cleanup(DataDefinitionInterface $definition, array $objectIds): void
{
    foreach ($objectIds as $objectId) {
        $object = \OpenDxp\Model\DataObject::getById($objectId);
        
        if ($object) {
            $object->delete();
        }
    }
}
```

### Extending AbstractCleaner

For convenience, you can extend the `AbstractCleaner` class which provides common functionality:

```php
<?php

namespace AcmeBundle\DataDefinitions\Cleaner;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\Cleaner\AbstractCleaner;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\DataDefinitionInterface;

class MyCustomCleaner extends AbstractCleaner
{
    public function cleanup(DataDefinitionInterface $definition, array $objectIds): void
    {
        // Use helper methods from AbstractCleaner
        $class = $definition->getClass();
        
        foreach ($objectIds as $objectId) {
            $object = $this->getObject($objectId, $class);
            
            if ($object) {
                // Your custom clean-up logic
            }
        }
    }
}
```

### When to Use Custom Cleaners

Create a custom cleaner when you need to:
- Implement custom deletion logic (e.g., move to archive instead of delete)
- Handle special object relationships before deletion
- Send notifications or trigger events when objects are cleaned up
- Implement conditional clean-up based on object properties
- Integrate with external systems during clean-up

## Persister

A persister handles the final save process for imported objects. It determines how and when objects are persisted to the database.

### Built-in Persisters

Data Definitions includes one built-in persister:
- **Persister** - Saves objects using the standard OpenDxp persistence mechanism

### Creating a Custom Persister

To create your own persister, implement the `Instride\Bundle\DataDefinitionsBundle\Persister\PersisterInterface` interface:

```php
<?php

namespace AcmeBundle\DataDefinitions\Persister;

use Instride\Bundle\DataDefinitionsBundle\Model\ImportDefinitionInterface;
use Instride\Bundle\DataDefinitionsBundle\Persister\PersisterInterface;
use OpenDxp\Model\DataObject\Concrete;

class MyCustomPersister implements PersisterInterface
{
    public function persist(Concrete $object, ImportDefinitionInterface $definition, array $params): void
    {
        // Your custom persistence logic here
        // This is called after all setters have been applied
        
        // Example: Add custom validation before saving
        if (!$this->validateObject($object, $definition)) {
            throw new \Exception('Object validation failed');
        }
        
        // Example: Add custom metadata
        $object->setProperty('importedAt', new \DateTime());
        $object->setProperty('importSource', $definition->getName());
        
        // Save the object
        $object->save();
    }
    
    private function validateObject(Concrete $object, ImportDefinitionInterface $definition): bool
    {
        // Implement your validation logic
        return true;
    }
}
```

### Registering the Persister

Add your persister as a service with the `data_definitions.persister` tag:

```yml
services:
    acme_bundle.data_definitions.my_custom_persister:
        class: AcmeBundle\DataDefinitions\Persister\MyCustomPersister
        tags:
            - { name: data_definitions.persister, type: my-custom-persister }
```

**Tag attributes:**
- `type`: A unique identifier for your persister (used in the UI)

### Persister Parameters

The `persist` method receives:
- `$object`: The DataObject to persist
- `$definition`: The import definition
- `$params`: Additional parameters

### Example: Versioning Persister

A persister that creates a version before saving:

```php
<?php

namespace AcmeBundle\DataDefinitions\Persister;

use Instride\Bundle\DataDefinitionsBundle\Model\ImportDefinitionInterface;
use Instride\Bundle\DataDefinitionsBundle\Persister\PersisterInterface;
use OpenDxp\Model\DataObject\Concrete;

class VersioningPersister implements PersisterInterface
{
    public function persist(Concrete $object, ImportDefinitionInterface $definition, array $params): void
    {
        if ($object->getId() && !$object->getPublished()) {
            // Create a version for existing unpublished objects
            $object->save();
        } else {
            // Standard save
            $object->save();
        }
    }
}
```

### Example: Async Persister

A persister that queues objects for async processing:

```php
<?php

namespace AcmeBundle\DataDefinitions\Persister;

use Instride\Bundle\DataDefinitionsBundle\Model\ImportDefinitionInterface;
use Instride\Bundle\DataDefinitionsBundle\Persister\PersisterInterface;
use OpenDxp\Model\DataObject\Concrete;

class AsyncPersister implements PersisterInterface
{
    private $queue;
    
    public function __construct(MessageBusInterface $queue)
    {
        $this->queue = $queue;
    }
    
    public function persist(Concrete $object, ImportDefinitionInterface $definition, array $params): void
    {
        // Queue the object for async processing
        $message = new PersistObjectMessage($object->getId(), $definition->getId());
        $this->queue->dispatch($message);
    }
}
```

### When to Use Custom Persisters

Create a custom persister when you need to:
- Implement custom validation before saving
- Add metadata or audit information to objects
- Implement versioning strategies
- Integrate with external systems during save
- Implement async or batch saving
- Handle special save scenarios (e.g., publishing workflows)
- Add custom error handling or logging during save

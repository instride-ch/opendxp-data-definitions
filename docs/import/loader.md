## Loader

A loader finds an existing OpenDxp Object (or returns null if not found) based on the import data. By default, Data Definitions comes with one Loader: `PrimaryKeyLoader`, which finds DataObjects based on un-interpreted data according to the configuration.

### Creating a Custom Loader

To create your own loader, implement the `Instride\Bundle\OpenDxpDataDefinitionsBundle\Loader\LoaderInterface` interface:

```php
<?php

namespace AcmeBundle\DataDefinitions\Loader;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\Context\LoaderContextInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Loader\LoaderInterface;
use OpenDxp\Model\DataObject\Concrete;

class MyCustomLoader implements LoaderInterface
{
    public function load(LoaderContextInterface $context): ?Concrete
    {
        $definition = $context->getDefinition();
        $dataRow = $context->getDataRow();
        $params = $context->getParams();
        
        // Your custom loading logic here
        // Find and return the object, or null if not found
        $object = $this->findObject($dataRow, $definition);
        
        return $object;
    }
    
    private function findObject(array $dataRow, $definition): ?Concrete
    {
        // Implement your object finding logic
        // For example, find by custom field, external ID, etc.
        return null;
    }
}
```

### Registering the Loader

Add your loader as a service with the `data_definitions.loader` tag:

```yaml
services:
    acme_bundle.data_definitions.my_custom_loader:
        class: AcmeBundle\DataDefinitions\Loader\MyCustomLoader
        tags:
            - { name: data_definitions.loader, type: my-custom-loader, form-type: AcmeBundle\Form\Type\MyCustomLoaderType }
```

**Tag attributes:**
- `type`: A unique identifier for your loader (used in the UI)
- `form-type`: (Optional) The form type class for configuring your loader (see below for implementation)

### Adding Configuration Form

If your loader requires configuration, create a form type:

```php
<?php

namespace AcmeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MyCustomLoaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identifierField', TextType::class, [
                'required' => false,
                'label' => 'Identifier Field',
            ])
        ;
    }
}
```

### Accessing Configuration

In your loader, you can access the configuration through the definition:

```php
public function load(LoaderContextInterface $context): ?Concrete
{
    $definition = $context->getDefinition();
    $config = $definition->getLoaderConfig();
    
    $identifierField = $config['identifierField'] ?? 'id';
    
    // Use the configuration in your logic
}
```

### Loader Context

The `LoaderContextInterface` provides access to:

- `getDefinition()`: The import definition
- `getDataRow()`: The current data row being processed
- `getParams()`: Additional parameters
- `getObject()`: The current object (if already loaded)

### Example: PrimaryKeyLoader

The built-in `PrimaryKeyLoader` demonstrates a complete implementation. It:
- Finds objects based on primary key configuration
- Supports multiple identifier fields
- Handles object creation if not found (based on configuration)

### When to Use Custom Loaders

Create a custom loader when you need to:
- Find objects by custom criteria (e.g., external ID, SKU, email)
- Implement complex lookup logic
- Integrate with external systems for object identification
- Handle special object relationships

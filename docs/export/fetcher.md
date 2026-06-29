## Fetcher

A fetcher finds and returns OpenDxp Objects for export. By default, Data Definitions comes with one Fetcher: `ObjectsFetcher`, which returns all Objects of a specific type.

### Creating a Custom Fetcher

To create your own fetcher, you need to implement the `Instride\Bundle\OpenDxpDataDefinitionsBundle\Fetcher\FetcherInterface` interface:

```php
<?php

namespace AcmeBundle\DataDefinitions\Fetcher;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\Context\FetcherContextInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Fetcher\FetcherInterface;

class MyCustomFetcher implements FetcherInterface
{
    public function fetch(
        FetcherContextInterface $context,
        int $limit,
        int $offset,
    ): array {
        // Fetch objects based on the export definition and parameters
        // Return an array of objects
        $definition = $context->getDefinition();
        $params = $context->getParams();
        
        // Your custom fetching logic here
        $objects = [];
        
        return $objects;
    }

    public function count(FetcherContextInterface $context): int
    {
        // Return the total count of objects that would be fetched
        $definition = $context->getDefinition();
        $params = $context->getParams();
        
        // Your custom counting logic here
        return 0;
    }
}
```

### Registering the Fetcher

Add your fetcher as a service with the `data_definitions.fetcher` tag:

```yaml
services:
    acme_bundle.data_definitions.my_custom_fetcher:
        class: AcmeBundle\DataDefinitions\Fetcher\MyCustomFetcher
        tags:
            - { name: data_definitions.fetcher, type: my-custom-fetcher, form-type: AcmeBundle\Form\Type\MyCustomFetcherType }
```

**Tag attributes:**
- `type`: A unique identifier for your fetcher (used in the UI)
- `form-type`: (Optional) The form type class for configuring your fetcher (see below for implementation)

### Adding Configuration Form

If your fetcher requires configuration, create a form type:

```php
<?php

namespace AcmeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MyCustomFetcherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customOption', TextType::class, [
                'required' => false,
                'label' => 'Custom Option',
            ])
        ;
    }
}
```

### Accessing Configuration

In your fetcher, you can access the configuration through the definition:

```php
public function fetch(FetcherContextInterface $context, int $limit, int $offset): array
{
    $definition = $context->getDefinition();
    $config = $definition->getFetcherConfig();
    
    $customOption = $config['customOption'] ?? null;
    
    // Use the configuration in your logic
}
```

### Example: ObjectsFetcher

The built-in `ObjectsFetcher` demonstrates a complete implementation. It:
- Fetches objects based on class definition
- Supports filtering by root path, query, conditions, and IDs
- Handles unpublished objects
- Provides stable sorting across pages

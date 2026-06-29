## Import Providers

Import providers handle reading data from various sources and making it available for import. They parse source files and provide structured data to the import process.

### Supported Import Providers

By default, Data Definitions supports these import provider types:
- **CSV** - Comma-separated values
- **JSON** - JSON format
- **XML** - XML format
- **SQL** - Direct SQL database queries
- **External SQL** - External SQL database connections
- **Raw** - For nested imports
- **XLSX** - Excel format

### Creating a Custom Import Provider

To create a custom import provider, implement the `Instride\Bundle\OpenDxpDataDefinitionsBundle\Provider\ImportProviderInterface` interface:

```php
<?php

namespace AcmeBundle\DataDefinitions\Provider;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\Filter\FilterInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\ImportDefinitionInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Provider\ImportProviderInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Provider\ImportDataSetInterface;

class MyCustomProvider implements ImportProviderInterface
{
    public function testData(array $configuration): bool
    {
        // Test if the configuration is valid and data source is accessible
        // Return true if test passes, false otherwise
        return true;
    }

    public function getColumns(array $configuration): array
    {
        // Return an array of available columns from the data source
        // Each column should be an instance of FromColumn
        $columns = [];
        
        // Your custom column detection logic here
        $columns[] = new FromColumn('column1', 'Column 1');
        $columns[] = new FromColumn('column2', 'Column 2');
        
        return $columns;
    }

    public function getData(
        array $configuration,
        ImportDefinitionInterface $definition,
        array $params,
        FilterInterface $filter = null,
    ): ImportDataSetInterface {
        // Return the data as an ImportDataSetInterface
        // The filter can be applied to filter rows during data retrieval
        
        $offset = $params['offset'] ?? 0;
        $limit = $params['limit'] ?? null;
        
        // Your custom data loading logic here
        $data = $this->loadData($configuration, $offset, $limit);
        
        return new TraversableImportDataSet($data);
    }
    
    private function loadData(array $configuration, int $offset, ?int $limit): iterable
    {
        // Load and return data from your source
        return [];
    }
}
```

### Registering the Import Provider

Add your import provider as a service with the `data_definitions.import_provider` tag:

```yaml
services:
    acme_bundle.data_definitions.my_custom_provider:
        class: AcmeBundle\DataDefinitions\Provider\MyCustomProvider
        tags:
            - { name: data_definitions.import_provider, type: my-custom-provider, form-type: AcmeBundle\Form\Type\MyCustomProviderType }
```

**Tag attributes:**
- `type`: A unique identifier for your provider (used in the UI)
- `form-type`: The form type class for configuring your provider

### Adding Configuration Form

Create a form type for your provider configuration:

```php
<?php

namespace AcmeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class MyCustomProviderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('delimiter', TextType::class, [
                'required' => false,
                'label' => 'Delimiter',
                'data' => ',',
            ])
            ->add('encoding', TextType::class, [
                'required' => false,
                'label' => 'Encoding',
                'data' => 'UTF-8',
            ])
        ;
    }
}
```

### Accessing Configuration

Access the provider configuration through the definition:

```php
public function getData(
    array $configuration,
    ImportDefinitionInterface $definition,
    array $params,
    FilterInterface $filter = null,
): ImportDataSetInterface {
    $delimiter = $configuration['delimiter'] ?? ',';
    $encoding = $configuration['encoding'] ?? 'UTF-8';
    
    // Use configuration in your data loading logic
}
```

### Import Parameters

The `$params` array typically contains:
- `offset`: Number of rows to skip (for pagination)
- `limit`: Maximum number of rows to return
- `file`: Path to the source file (for file-based providers)

### Example: CSV Provider

The built-in `CsvProvider` demonstrates a complete implementation. It:
- Parses CSV files with configurable delimiters and enclosures
- Supports custom headers
- Handles offset and limit for pagination
- Applies filters during data retrieval

### Testing Your Provider

Test your provider by creating an import definition and selecting your custom provider. Configure it through the form and run a test import to verify the data is correctly parsed and available for mapping.

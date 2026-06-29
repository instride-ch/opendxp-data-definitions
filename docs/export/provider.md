## Export Providers

Export providers handle the actual export of data to various formats. They receive data row by row and generate the final export file.

### Supported Export Providers

By default, Data Definitions supports these export provider types:
- **CSV** - Comma-separated values
- **JSON** - JSON format
- **XML** - XML format
- **XLSX** - Excel format

### Creating a Custom Export Provider

To create a custom export provider, implement the `Instride\Bundle\OpenDxpDataDefinitionsBundle\Provider\ExportProviderInterface` interface:

```php
<?php

namespace AcmeBundle\DataDefinitions\Provider;

use Instride\Bundle\OpenDxpDataDefinitionsBundle\Model\ExportDefinitionInterface;
use Instride\Bundle\OpenDxpDataDefinitionsBundle\Provider\ExportProviderInterface;

class MyCustomProvider implements ExportProviderInterface
{
    private array $exportData = [];

    public function addExportData(
        array $data,
        array $configuration,
        ExportDefinitionInterface $definition,
        array $params,
    ): void {
        // Store data row for later export
        $this->exportData[] = $data;
    }

    public function exportData(array $configuration, ExportDefinitionInterface $definition, array $params): void
    {
        // Generate the final export file
        $file = $this->getFile($params);
        
        // Your custom export logic here
        $this->writeToFile($file, $this->exportData, $configuration);
    }
    
    private function getFile(array $params): string
    {
        // Determine the output file path from params
        return $params['file'] ?? '/tmp/export.txt';
    }
    
    private function writeToFile(string $file, array $data, array $configuration): void
    {
        // Write data to file in your custom format
        file_put_contents($file, json_encode($data));
    }
}
```

### Registering the Export Provider

Add your export provider as a service with the `data_definitions.export_provider` tag:

```yaml
services:
    acme_bundle.data_definitions.my_custom_provider:
        class: AcmeBundle\DataDefinitions\Provider\MyCustomProvider
        tags:
            - { name: data_definitions.export_provider, type: my-custom-provider, form-type: AcmeBundle\Form\Type\MyCustomProviderType }
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
public function exportData(array $configuration, ExportDefinitionInterface $definition, array $params): void
{
    $delimiter = $configuration['delimiter'] ?? ',';
    $encoding = $configuration['encoding'] ?? 'UTF-8';
    
    // Use configuration in your export logic
}
```

### Example: CSV Provider

The built-in `CsvProvider` demonstrates a complete implementation:

```php
public function addExportData(
    array $data,
    array $configuration,
    ExportDefinitionInterface $definition,
    array $params,
): void {
    $this->exportData[] = $data;
}

public function exportData(array $configuration, ExportDefinitionInterface $definition, array $params): void
{
    if (!array_key_exists('file', $params)) {
        return;
    }

    $file = $this->getFile($params);
    $headers = count($this->exportData) > 0 ? array_keys($this->exportData[0]) : [];

    $writer = Writer::createFromPath($file, 'w+');
    $writer->setDelimiter($configuration['delimiter']);
    $writer->setEnclosure($configuration['enclosure']);
    
    if (isset($configuration['escape'])) {
        $writer->setEscape($configuration['escape']);
    }
    
    $writer->insertOne($headers);
    $writer->insertAll($this->exportData);
}
```

### Export Parameters

The `$params` array typically contains:
- `file`: The output file path
- `root`: Root node ID for hierarchical exports
- `query`: Search query for filtering
- `condition`: SQL condition for filtering
- `ids`: Array of specific object IDs to export

### Testing Your Provider

Test your provider by creating an export definition and selecting your custom provider. Configure it through the form and run the export to verify the output format.

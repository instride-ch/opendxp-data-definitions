## Filter

A filter determines whether a data row should be imported or skipped. The filter method is called for each row during the import process, allowing you to implement custom filtering logic.

### Creating a Custom Filter

To create a custom filter, implement the `Instride\Bundle\DataDefinitionsBundle\Filter\FilterInterface` interface:

```php
<?php

namespace AcmeBundle\DataDefinitions\Filter;

use Instride\Bundle\DataDefinitionsBundle\Context\FilterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Filter\FilterInterface;

class MyCustomFilter implements FilterInterface
{
    public function filter(FilterContextInterface $context): bool
    {
        $dataRow = $context->getDataRow();
        $definition = $context->getDefinition();
        $params = $context->getParams();
        
        // Your custom filtering logic here
        // Return true to import the row, false to skip it
        
        if ($this->shouldImport($dataRow, $definition)) {
            return true; // Will be imported
        }
        
        return false; // Will be ignored
    }
    
    private function shouldImport(array $dataRow, $definition): bool
    {
        // Implement your filtering logic
        // For example: check if a field has a specific value
        return isset($dataRow['isActive']) && $dataRow['isActive'] === true;
    }
}
```

### Registering the Filter

Add your filter as a service with the `data_definitions.filter` tag:

```yml
services:
    acme_bundle.data_definitions.my_custom_filter:
        class: AcmeBundle\DataDefinitions\Filter\MyCustomFilter
        tags:
            - { name: data_definitions.filter, type: my-custom-filter }
```

**Tag attributes:**
- `type`: A unique identifier for your filter (used in the UI)

### Filter Context

The `FilterContextInterface` provides access to:

- `getDataRow()`: The current data row being processed
- `getDefinition()`: The import definition
- `getParams()`: Additional parameters
- `getObject()`: The current object (if already loaded)

### Example: Status Filter

A practical example of filtering based on a status field:

```php
<?php

namespace AcmeBundle\DataDefinitions\Filter;

use Instride\Bundle\DataDefinitionsBundle\Context\FilterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Filter\FilterInterface;

class StatusFilter implements FilterInterface
{
    public function filter(FilterContextInterface $context): bool
    {
        $dataRow = $context->getDataRow();
        
        // Only import rows with status 'active' or 'pending'
        $status = $dataRow['status'] ?? '';
        
        return in_array(strtolower($status), ['active', 'pending'], true);
    }
}
```

### Example: Date Range Filter

Filter based on date ranges:

```php
<?php

namespace AcmeBundle\DataDefinitions\Filter;

use Instride\Bundle\DataDefinitionsBundle\Context\FilterContextInterface;
use Instride\Bundle\DataDefinitionsBundle\Filter\FilterInterface;
use DateTime;

class DateRangeFilter implements FilterInterface
{
    public function filter(FilterContextInterface $context): bool
    {
        $dataRow = $context->getDataRow();
        $config = $context->getDefinition()->getFilterConfig();
        
        $dateString = $dataRow['date'] ?? null;
        if (!$dateString) {
            return false;
        }
        
        $date = new DateTime($dateString);
        
        $fromDate = isset($config['fromDate']) ? new DateTime($config['fromDate']) : null;
        $toDate = isset($config['toDate']) ? new DateTime($config['toDate']) : null;
        
        if ($fromDate && $date < $fromDate) {
            return false;
        }
        
        if ($toDate && $date > $toDate) {
            return false;
        }
        
        return true;
    }
}
```

### Adding Configuration Form

If your filter requires configuration, create a form type:

```php
<?php

namespace AcmeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class DateRangeFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fromDate', DateType::class, [
                'required' => false,
                'label' => 'From Date',
                'widget' => 'single_text',
            ])
            ->add('toDate', DateType::class, [
                'required' => false,
                'label' => 'To Date',
                'widget' => 'single_text',
            ])
        ;
    }
}
```

Then register the filter with the form type:

```yml
services:
    acme_bundle.data_definitions.date_range_filter:
        class: AcmeBundle\DataDefinitions\Filter\DateRangeFilter
        tags:
            - { name: data_definitions.filter, type: date-range, form-type: AcmeBundle\Form\Type\DateRangeFilterType }
```

### When to Use Custom Filters

Create a custom filter when you need to:
- Filter based on complex business rules
- Implement conditional logic based on multiple fields
- Filter based on external data or API calls
- Implement date/time-based filtering
- Filter based on relationships or dependencies
- Implement custom validation before import
